<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class PjController extends Controller
{
    public function __construct()
    {
        $this->middleware('cek.login:pj');
    }

    public function index(Request $request)
    {
        $namaPj = session('nama_lengkap');

        $query = Ticket::whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->with('pelapor');

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(5)->withQueryString();

        $counts = Ticket::whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->selectRaw("
                COUNT(CASE WHEN status = 'Open' THEN 1 END) as menunggu,
                COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as diproses,
                COUNT(CASE WHEN status IN ('Resolved', 'Closed') THEN 1 END) as selesai
            ")
            ->first();

        return view('pj.dashboard', [
            'tickets'      => $tickets,
            'menunggu'     => $counts->menunggu ?? 0,
            'diproses'     => $counts->diproses ?? 0,
            'selesai'      => $counts->selesai ?? 0,
            'statusFilter' => $request->input('status', 'semua'),
        ]);
    }

    public function terima(string $id)
    {
        $namaPj = session('nama_lengkap');
        $ticket = Ticket::where('id', $id)
            ->whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->where('status', 'Open')
            ->firstOrFail();

        $ticket->status = 'In Progress';
        $ticket->timestamps = false;
        $ticket->save();

        return redirect()->route('pj.dashboard')
            ->with('success', 'Tiket #' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) . ' mulai dikerjakan.');
    }

    public function selesaikan(Request $request, string $id)
    {
        $request->validate([
            'catatan_penyelesaian' => 'nullable|string|max:1000',
            'bukti_foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_foto.required' => 'Foto bukti penyelesaian wajib diunggah sesuai SOP.',
        ]);

        $namaPj = session('nama_lengkap');
        $ticket = Ticket::where('id', $id)
            ->whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->where('status', 'In Progress')
            ->firstOrFail();

        $path = $request->file('bukti_foto')->store('tickets_resolved', 'public');

        $ticket->status = 'Resolved';
        $ticket->tanggal_selesai = now();
        $ticket->hasil_resolved_foto = $path;

        if ($request->filled('catatan_penyelesaian')) {
            $ticket->deskripsi_masalah .= "\n\n[Catatan PJ - " . now()->format('Y-m-d H:i') . "]: "
                . strip_tags($request->catatan_penyelesaian);
        }

        $ticket->timestamps = false;
        $ticket->save();

        return redirect()->route('pj.dashboard')
            ->with('success', 'Tiket #' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) . ' berhasil diselesaikan.');
    }

    public function show(string $id)
    {
        $namaPj = session('nama_lengkap');

        $ticket = Ticket::where('id', $id)
            ->whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->firstOrFail();

        return response()->json($ticket);
    }
}