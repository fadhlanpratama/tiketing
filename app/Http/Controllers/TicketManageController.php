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

    // Halaman: Penugasan PJ + Verifikasi Resolved
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

        return view('admin.tickets', compact('unassignedTickets', 'resolvedTickets', 'activePjs'));
    }

    // Halaman: Semua Tiket
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

        $ticket = Ticket::where('id', $id)->firstOrFail();
        $pj = Users::where('id', $request->penanggung_jawab)
            ->where('role', 'pj')
            ->where('status', 'active')
            ->firstOrFail();

        $ticket->pj_id = $pj->id;
        $ticket->penanggung_jawab = $pj->nama_lengkap;
        $ticket->user_notif_assigned_read = false;
        $ticket->pj_notif_assigned_read = false;
        $ticket->save();

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Tiket #' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) . ' berhasil ditugaskan ke PJ: ' . $pj->nama_lengkap);
    }

    public function close(string $id)
    {
        $ticket = Ticket::where('id', $id)->where('status', 'Resolved')->firstOrFail();

        $ticket->status = 'Closed';
        $ticket->closed_by = 'admin';
        $ticket->user_notif_admin_closed_read = false;
        $ticket->pj_notif_admin_closed_read = false;
        $ticket->save();

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Tiket #' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) . ' resmi ditutup (Closed).');
    }

    public function show(string $id)
    {
        $ticket = Ticket::with('pelapor', 'messages')->findOrFail($id);

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

        return view('admin.detail', compact('ticket', 'activePjs'));
    }
}