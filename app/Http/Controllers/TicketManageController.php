<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Users;
use Illuminate\Validation\Rule;

class TicketManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('cek.login:admin');
    }

    public function index()
    {
        $unassignedTickets = Ticket::where('status', 'Open')
            ->where(function ($q) {
                $q->whereNull('pj_id')
                    ->orWhereNull('penanggung_jawab')
                    ->orWhere('penanggung_jawab', '');
            })
            ->with('pelapor')
            ->orderBy('created_at', 'asc')
            ->get();

        $resolvedTickets = Ticket::where('status', 'Resolved')
            ->with('pelapor')
            ->orderBy('updated_at', 'desc')
            ->get();

        $activePjs = Users::where('role', 'pj')
            ->where('status', 'active')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        foreach ($activePjs as $pj) {
            $pj->sla_stats = $this->calculatePjSlaStats($pj->id);
        }

        return view('admin.tickets', compact('unassignedTickets', 'resolvedTickets', 'activePjs'));
    }

    public function all()
    {
        $allTickets = Ticket::with('pelapor')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.all-tickets', compact('allTickets'));
    }

    public function assignPJ(Request $request, string $id)
    {
        $request->validate([
            'penanggung_jawab' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'pj')->where('status', 'active');
                }),
            ],
        ], [
            'penanggung_jawab.required' => 'Pilih PJ/Teknisi terlebih dahulu.',
            'penanggung_jawab.integer'  => 'Data PJ tidak valid.',
            'penanggung_jawab.exists'   => 'PJ/Teknisi tidak ditemukan atau tidak aktif.',
        ]);

        $pj = Users::where('id', $request->penanggung_jawab)
            ->where('role', 'pj')
            ->where('status', 'active')
            ->firstOrFail();

        $updated = Ticket::where('id', $id)
            ->where('status', 'Open')
            ->update([
                'pj_id' => $pj->id,
                'penanggung_jawab' => $pj->nama_lengkap,
                'user_notif_assigned_read' => false,
                'pj_notif_assigned_read' => false,
            ]);

        if (!$updated) {
            return back()->with('error', 'Tiket sudah diproses atau tidak lagi berstatus Open.');
        }

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Tiket #' . str_pad($id, 5, '0', STR_PAD_LEFT) . ' berhasil ditugaskan ke PJ: ' . $pj->nama_lengkap);
    }

    public function close(string $id)
    {
        $updated = Ticket::where('id', $id)
            ->where('status', 'Resolved')
            ->update([
                'status' => 'Closed',
                'closed_by' => 'admin',
                'user_notif_admin_closed_read' => false,
                'pj_notif_admin_closed_read' => false,
            ]);

        if (!$updated) {
            return back()->with('error', 'Tiket sudah ditutup atau tidak lagi berstatus Resolved.');
        }

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Tiket #' . str_pad($id, 5, '0', STR_PAD_LEFT) . ' resmi ditutup (Closed).');
    }

    public function show(string $id)
    {
        $ticket = Ticket::with('pelapor', 'messages', 'collaborators.pj')->findOrFail($id);

        if ($ticket->status === 'Closed'
            && $ticket->closed_by === 'user'
            && !$ticket->admin_notif_user_closed_read) {
            $ticket->admin_notif_user_closed_read = true;
            $ticket->timestamps = false;
            $ticket->save();
        }

        if (!$ticket->admin_notif_new_ticket_read) {
            $ticket->admin_notif_new_ticket_read = true;
            $ticket->timestamps = false;
            $ticket->save();
        }

        $activePjs = Users::where('role', 'pj')
            ->where('status', 'active')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        foreach ($activePjs as $pj) {
            $pj->sla_stats = $this->calculatePjSlaStats($pj->id);
        }

        return view('admin.detail', compact('ticket', 'activePjs'));
    }

    private function calculatePjSlaStats(int $pjId): array
    {
        $baseQuery = fn () => Ticket::where('pj_id', $pjId);

        $counts = $baseQuery()->selectRaw("
                COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as diproses,
                COUNT(CASE WHEN status = 'Resolved' OR (status = 'Closed' AND (closed_by = 'admin' OR closed_by IS NULL)) THEN 1 END) as selesai
            ")
            ->first();

        $slaTerlambat = $baseQuery()
            ->where(function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('closed_by', '!=', 'user')
                        ->orWhereNull('closed_by');
                });
            })
            ->where(function ($q) {
                $q->where(function ($qq) {
                    $qq->where('status', 'In Progress')
                        ->whereNotNull('waktu_mulai_dikerjakan')
                        ->whereNotNull('sla_target_menit')
                        ->whereRaw('TIMESTAMPADD(MINUTE, sla_target_menit, waktu_mulai_dikerjakan) < NOW()');
                })->orWhere('sla_status', 'Terlambat');
            })
            ->count();

        $diproses = $counts->diproses ?? 0;
        $selesai  = $counts->selesai ?? 0;
        $total    = $diproses + $selesai;
        $pct      = $total > 0 ? min(100, round(($slaTerlambat / $total) * 100)) : 0;

        return [
            'diproses'        => $diproses,
            'selesai'         => $selesai,
            'sla_terlambat'   => $slaTerlambat,
            'sla_percentage'  => $pct,
            'sla_needle_deg'  => -90 + ($pct / 100) * 180,
        ];
    }
}