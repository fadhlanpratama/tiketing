<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Users;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('cek.login');
    }

    public function index()
    {
        $userId = session('user_id');
        $tickets = Ticket::where('user_id', $userId)->with('pelapor')->latest()->paginate(5);
        $counts = Ticket::where('user_id', $userId)->selectRaw("
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
        $userId = session('user_id');
        $user = Users::find($userId);
        $counts = Ticket::where('user_id', $userId)->selectRaw("
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
            'sub_kategori_manual'  => 'required_if:sub_kategori,Lainnya|nullable|string|max:100',
            'deskripsi_masalah' => 'required|string|max:2000',
            'nomor_bmn'         => 'nullable|string|max:30',
            'prioritas'         => 'required|in:Rendah,Sedang,Tinggi',
            'attachment_foto'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $ticket = new Ticket();
        $ticket->user_id           = session('user_id');
        $ticket->kategori          = $request->kategori;
        $ticket->sub_kategori      = $request->sub_kategori;
        $ticket->deskripsi_masalah = strip_tags($request->deskripsi_masalah);
        $ticket->nomor_bmn         = strip_tags($request->nomor_bmn);
        $ticket->prioritas         = $request->prioritas;
        $ticket->status            = 'Open'; 

        if ($request->sub_kategori === 'Lainnya') {
            $ticket->sub_kategori  = strip_tags($request->sub_kategori_manual);
        } else {
            $ticket->sub_kategori  = $request->sub_kategori;
        }

        if ($request->filled('nomor_bmn')) {
            $ticket->nomor_bmn = strip_tags($request->nomor_bmn);
        } else {
            $ticket->nomor_bmn = 'Non-BMN';
        }

        if ($request->hasFile('attachment_foto')) {
            $path = $request->file('attachment_foto')->store('tickets_attachment', 'public');
            $ticket->attachment_foto = $path;
        }

        $ticket->save();

        return redirect()->route('user.dashboard')->with('success', 'Tiket #' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) . ' berhasil dibuat!');
    }

    public function edit(string $id)
    {
        $userId = session('user_id');
        $ticket = Ticket::where('id', $id)->where('user_id', $userId)->firstOrFail();
        $counts = Ticket::where('user_id', $userId)->selectRaw("
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
            'sub_kategori_manual'  => 'required_if:sub_kategori,Lainnya|nullable|string|max:100',
            'deskripsi_masalah' => 'required|string|max:2000',
            'nomor_bmn'         => 'nullable|string|max:30',
            'prioritas'         => 'required|in:Rendah,Sedang,Tinggi',
            'attachment_foto'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $userId = session('user_id');
        $ticket = Ticket::where('id', $id)->where('user_id', $userId)->firstOrFail();
        $ticket->kategori          = $request->kategori;
        $ticket->sub_kategori      = $request->sub_kategori;
        $ticket->deskripsi_masalah = strip_tags($request->deskripsi_masalah);
        $ticket->nomor_bmn         = strip_tags($request->nomor_bmn);
        $ticket->prioritas         = $request->prioritas;
        
        if ($request->sub_kategori === 'Lainnya') {
            $ticket->sub_kategori  = strip_tags($request->sub_kategori_manual);
        } else {
            $ticket->sub_kategori  = $request->sub_kategori;
        }

        if ($request->filled('nomor_bmn')) {
            $ticket->nomor_bmn = strip_tags($request->nomor_bmn);
        } else {
            $ticket->nomor_bmn = 'Non-BMN';
        }

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
        $userId = session('user_id');
        $ticket = Ticket::where('id', $id)->where('user_id', $userId)->firstOrFail();

        if ($ticket->attachment_foto) {
            Storage::disk('public')->delete($ticket->attachment_foto);
        }

        $ticket->forceDelete(); 

        return redirect()->route('user.dashboard')->with('success', 'Tiket berhasil dihapus permanen!');
    }
}