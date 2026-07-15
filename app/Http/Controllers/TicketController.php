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

    public function index(Request $request)
    {
        $userId = session('user_id');

        $query = Ticket::where('user_id', $userId)->with('pelapor');

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(5)->withQueryString();

        $counts = Ticket::where('user_id', $userId)->selectRaw("
            COUNT(CASE WHEN status = 'Open' THEN 1 END) as aktif,
            COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as proses,
            COUNT(CASE WHEN status IN ('Resolved', 'Closed') THEN 1 END) as selesai
        ")->first();

        return view('user.dashboard', [
            'tickets'      => $tickets,
            'TiketAktif'   => $counts->aktif ?? 0,
            'dalamProses'  => $counts->proses ?? 0,
            'selesai'      => $counts->selesai ?? 0,
            'statusFilter' => $request->input('status', 'semua'),
        ]);
    }

    public function create()
    {
        $userId = session('user_id');
        $user = Users::find($userId);
        $counts = Ticket::where('user_id', $userId)->selectRaw("
            COUNT(CASE WHEN status = 'Open' THEN 1 END) as aktif,
            COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as proses,
            COUNT(CASE WHEN status IN ('Resolved', 'Closed') THEN 1 END) as selesai
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
            'kategori'             => 'required|in:IT—Software,IT—Hardware,IT—Jaringan,Administrasi,Sarana—Prasarana,Keamanan,Kebersihan,Lainnya',
            'sub_kategori'         => 'required|string|max:100',
            'sub_kategori_manual'  => 'required_if:sub_kategori,Lainnya|nullable|string|max:100',
            'deskripsi_masalah'    => 'required|string|max:2000',
            'nomor_bmn'            => 'nullable|string|max:30',
            'prioritas'            => 'required|in:Rendah,Sedang,Tinggi',
            'attachment_foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $ticket = new Ticket();
        $ticket->user_id           = session('user_id');
        $ticket->kategori          = $request->kategori;
        $ticket->deskripsi_masalah = strip_tags($request->deskripsi_masalah);
        $ticket->prioritas         = $request->prioritas;
        $ticket->status            = 'Open';

        if ($request->sub_kategori === 'Lainnya') {
            $ticket->sub_kategori  = strip_tags($request->sub_kategori_manual);
        } else {
            $ticket->sub_kategori  = $request->sub_kategori;
        }

        $ticket->nomor_bmn = $request->filled('nomor_bmn') ? strip_tags($request->nomor_bmn) : 'Non-BMN';

        if ($request->hasFile('attachment_foto')) {
            $ticket->attachment_foto = $request->file('attachment_foto')->store('tickets_attachment', 'public');
        }

        $ticket->save();

        return redirect()->route('user.dashboard')->with('success', 'Tiket #' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) . ' berhasil dibuat!');
    }

    public function edit(string $id)
    {
        $userId = session('user_id');
        $ticket = Ticket::where('id', $id)->where('user_id', $userId)->where('status', 'Open')->firstOrFail();
        $counts = Ticket::where('user_id', $userId)->selectRaw("
            COUNT(CASE WHEN status = 'Open' THEN 1 END) as aktif,
            COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as proses,
            COUNT(CASE WHEN status IN ('Resolved', 'Closed') THEN 1 END) as selesai
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
            'kategori'             => 'required|in:IT—Software,IT—Hardware,IT—Jaringan,Administrasi,Sarana—Prasarana,Keamanan,Kebersihan,Lainnya',
            'sub_kategori'         => 'required|string|max:100',
            'sub_kategori_manual'  => 'required_if:sub_kategori,Lainnya|nullable|string|max:100',
            'deskripsi_masalah'    => 'required|string|max:2000',
            'nomor_bmn'            => 'nullable|string|max:30',
            'prioritas'            => 'required|in:Rendah,Sedang,Tinggi',
            'attachment_foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userId = session('user_id');
        $ticket = Ticket::where('id', $id)->where('user_id', $userId)->where('status', 'Open')->firstOrFail();

        $ticket->kategori          = $request->kategori;
        $ticket->deskripsi_masalah = strip_tags($request->deskripsi_masalah);
        $ticket->prioritas         = $request->prioritas;

        if ($request->sub_kategori === 'Lainnya') {
            $ticket->sub_kategori  = strip_tags($request->sub_kategori_manual);
        } else {
            $ticket->sub_kategori  = $request->sub_kategori;
        }

        $ticket->nomor_bmn = $request->filled('nomor_bmn') ? strip_tags($request->nomor_bmn) : 'Non-BMN';

        if ($request->hasFile('attachment_foto')) {
            if ($ticket->attachment_foto) {
                Storage::disk('public')->delete($ticket->attachment_foto);
            }
            $ticket->attachment_foto = $request->file('attachment_foto')->store('tickets_attachment', 'public');
        }

        if ($ticket->isDirty(['kategori', 'sub_kategori', 'deskripsi_masalah', 'nomor_bmn', 'prioritas', 'attachment_foto'])) {
        $ticket->user_edited_at = now();
    }

        $ticket->save();

        return redirect()->route('user.dashboard')->with('success', 'Tiket berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $userId = session('user_id');
        $ticket = Ticket::where('id', $id)->where('user_id', $userId)->where('status', 'Open')->firstOrFail();

        if ($ticket->attachment_foto) {
            Storage::disk('public')->delete($ticket->attachment_foto);
        }

        $ticket->forceDelete();

        return redirect()->route('user.dashboard')->with('success', 'Tiket berhasil dihapus permanen!');
    }
}