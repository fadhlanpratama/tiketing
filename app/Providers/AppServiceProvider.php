<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Ticket;
use App\Models\TicketMessage;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ================= NOTIFIKASI SISI PJ =================
        View::composer('pj.*', function ($view) {
            $namaPj = session('nama_lengkap');
            if (!$namaPj) return;

            $ticketIdsPj = Ticket::whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
                ->pluck('id');

            $notifMessages = TicketMessage::whereIn('ticket_id', $ticketIdsPj)
                ->where('sender_type', '!=', 'pj')
                ->where('read_by_pj', false)
                ->latest()
                ->take(10)
                ->get();

            $notifClosed = Ticket::whereIn('id', $ticketIdsPj)
                ->where('status', 'Closed')
                ->where('closed_by', 'user')
                ->where('pj_notif_closed_read', false)
                ->latest('updated_at')
                ->take(10)
                ->get();

            $view->with([
                'notifMessages' => $notifMessages,
                'notifClosed'   => $notifClosed,
                'notifCount'    => $notifMessages->count() + $notifClosed->count(),
            ]);
        });

        // ================= NOTIFIKASI SISI USER =================
        View::composer('user.*', function ($view) {
            $userId = session('user_id');
            if (!$userId) return;

            $ticketIdsUser = Ticket::where('user_id', $userId)->pluck('id');

            $notifMessagesUser = TicketMessage::whereIn('ticket_id', $ticketIdsUser)
                ->where('sender_type', 'pj')
                ->where('read_by_user', false)
                ->latest()
                ->take(10)
                ->get();

            $notifResolved = Ticket::whereIn('id', $ticketIdsUser)
                ->where('status', 'Resolved')
                ->where('user_notif_resolved_read', false)
                ->latest('tanggal_selesai')
                ->take(10)
                ->get();

            $view->with([
                'notifMessagesUser' => $notifMessagesUser,
                'notifResolved'     => $notifResolved,
                'notifCountUser'    => $notifMessagesUser->count() + $notifResolved->count(),
            ]);
        });
    }
}
