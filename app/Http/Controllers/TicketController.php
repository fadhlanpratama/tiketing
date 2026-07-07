<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Users;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        if (!session()->has('user_logged') || session('user_role') !== 'user') {
            return redirect()->route('home');
        }

        $tickets = Ticket::with('pelapor')->latest()->paginate(5);
        $counts = Ticket::selectRaw("
            COUNT(CASE WHEN status IN ('Open', 'In Progress') THEN 1 END) as aktif,
            COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as proses,
            COUNT(CASE WHEN status = 'Resolved' THEN 1 END) as selesai
        ")->first();

        return view('user.dashboard', [
            'tickets'     => $tickets,
            'TiketAktif'  => $counts->aktif ?? 0,
            'dalamProses' => $counts->proses ?? 0,
            'selesai'     => $counts->selesai ?? 0
        ]);
    }

    public function create()
    {
        if (!session()->has('user_logged') || session('user_role') !== 'user') {
            return redirect()->route('home');
        }

        $userId = session('user_id');
        $user = Users::find($userId);
        $counts = Ticket::selectRaw("
            COUNT(CASE WHEN status IN ('Open', 'In Progress') THEN 1 END) as aktif,
            COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as proses,
            COUNT(CASE WHEN status = 'Resolved' THEN 1 END) as selesai
        ")->first();

        return view('user.create', [
            'user'        => $user,
            'TiketAktif'  => $counts->aktif ?? 0,
            'dalamProses' => $counts->proses ?? 0,
            'selesai'     => $counts->selesai ?? 0
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori'          => 'required|string|max:50',
            'sub_kategori'      => 'required|string|max:100',
            'deskripsi_masalah' => 'required|string|max:2000',
            'nomor_bmn'         => 'required|string|max:30',
            'prioritas'         => 'required|in:Rendah,Sedang,Tinggi',
            'attachment_foto'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $ticket = new Ticket();
        $ticket->user_id           = session('user_id', 1); 
        $ticket->kategori          = $request->kategori;
        $ticket->sub_kategori      = $request->sub_kategori;
        $ticket->deskripsi_masalah = strip_tags($request->deskripsi_masalah);
        $ticket->nomor_bmn         = strip_tags($request->nomor_bmn);
        $ticket->prioritas         = $request->prioritas;
        $ticket->status            = 'Open'; 

        if ($request->hasFile('attachment_foto')) {
            $path = $request->file('attachment_foto')->store('tickets_attachment', 'public');
            $ticket->attachment_foto = $path;
        }

        $ticket->save();

        return redirect()->route('user.dashboard')->with('success', 'Tiket #' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) . ' berhasil dibuat!');
    }

    public function edit(string $id)
    {
        if (!session()->has('user_logged') || session('user_role') !== 'user') {
            return redirect()->route('home');
        }

        $ticket = Ticket::findOrFail($id);

        $counts = Ticket::selectRaw("
            COUNT(CASE WHEN status IN ('Open', 'In Progress') THEN 1 END) as aktif,
            COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as proses,
            COUNT(CASE WHEN status = 'Resolved' THEN 1 END) as selesai
        ")->first();

        return view('user.edit', [
            'ticket'      => $ticket,
            'TiketAktif'  => $counts->aktif ?? 0,
            'dalamProses' => $counts->proses ?? 0,
            'selesai'     => $counts->selesai ?? 0
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori'          => 'required|string|max:50',
            'sub_kategori'      => 'required|string|max:100',
            'deskripsi_masalah' => 'required|string|max:2000',
            'nomor_bmn'         => 'required|string|max:30',
            'prioritas'         => 'required|in:Rendah,Sedang,Tinggi',
            'attachment_foto'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->kategori          = $request->kategori;
        $ticket->sub_kategori      = $request->sub_kategori;
        $ticket->deskripsi_masalah = strip_tags($request->deskripsi_masalah);
        $ticket->nomor_bmn         = strip_tags($request->nomor_bmn);
        $ticket->prioritas         = $request->prioritas;

        if ($request->hasFile('attachment_foto')) {
            if ($ticket->attachment_foto) {
                Storage::disk('public')->delete($ticket->attachment_foto);
            }
            
            $path = $request->file('attachment_foto')->store('tickets_attachment', 'public');
            $ticket->attachment_foto = $path;
        }

        $ticket->save();

        return redirect()->route('user.dashboard')->with('success', 'Tiket berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        if ($ticket->attachment_foto) {
            Storage::disk('public')->delete($ticket->attachment_foto);
        }

        $ticket->delete();

        return redirect()->route('user.dashboard')->with('success', 'Tiket berhasil dihapus!');
    }
}