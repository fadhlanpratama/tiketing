<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\Ticket;
use App\Models\Users;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('cek.login:admin');
    }

    public function index()
    {
        $stats = [
            'pendingUsers'      => Users::where('status', 'pending')->count(),
            'unassignedTickets' => Ticket::where('status', 'Open')
                ->where(function ($q) {
                    $q->whereNull('pj_id')
                        ->orWhereNull('penanggung_jawab')
                        ->orWhere('penanggung_jawab', '');
                })->count(),
            'resolvedTickets' => Ticket::where('status', 'Resolved')->count(),
            'allTickets'      => Ticket::count(),
        ];

        $latestTickets = Ticket::with('pelapor')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestTickets'));
    }

    public function analytics()
    {
        return view('admin.analytics');
    }
}