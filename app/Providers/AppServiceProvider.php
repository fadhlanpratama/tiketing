<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\Users;


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

            $notifAssignedPj = Ticket::whereIn('id', $ticketIdsPj)
                ->where('pj_notif_assigned_read', false)
                ->latest('updated_at')
                ->take(10)
                ->get();

            $notifAdminClosedPj = Ticket::whereIn('id', $ticketIdsPj)
                ->where('status', 'Closed')
                ->where('closed_by', 'admin')
                ->where('pj_notif_admin_closed_read', false)
                ->latest('updated_at')
                ->take(10)
                ->get();

            $view->with([
                'notifMessages'      => $notifMessages,
                'notifClosed'        => $notifClosed,
                'notifAssignedPj'    => $notifAssignedPj,
                'notifAdminClosedPj' => $notifAdminClosedPj,
                'notifCount'         => $notifMessages->count() + $notifClosed->count() + $notifAssignedPj->count() + $notifAdminClosedPj->count(),
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

            $notifAssignedUser = Ticket::whereIn('id', $ticketIdsUser)
                ->where('user_notif_assigned_read', false)
                ->whereNotNull('penanggung_jawab')
                ->where('penanggung_jawab', '!=', '')
                ->latest('updated_at')
                ->take(10)
                ->get();

            $notifInProgress = Ticket::whereIn('id', $ticketIdsUser)
                ->where('status', 'In Progress')
                ->where('user_notif_inprogress_read', false)
                ->latest('updated_at')
                ->take(10)
                ->get();

            $notifAdminClosedUser = Ticket::whereIn('id', $ticketIdsUser)
                ->where('status', 'Closed')
                ->where('closed_by', 'admin')
                ->where('user_notif_admin_closed_read', false)
                ->latest('updated_at')
                ->take(10)
                ->get();

            $view->with([
                'notifMessagesUser'    => $notifMessagesUser,
                'notifResolved'        => $notifResolved,
                'notifAssignedUser'    => $notifAssignedUser,
                'notifInProgress'      => $notifInProgress,
                'notifAdminClosedUser' => $notifAdminClosedUser,
                'notifCountUser'       => $notifMessagesUser->count() + $notifResolved->count()
                                        + $notifAssignedUser->count() + $notifInProgress->count()
                                        + $notifAdminClosedUser->count(),
            ]);
        });

        // ================= NOTIFIKASI SISI ADMIN (tiket Resolved + pendaftar baru + tiket ditutup pelapor) =================
        View::composer('admin.*', function ($view) {
            $adminId = session('user_id');
            if (!$adminId) return;

            $notifResolvedAdmin = Ticket::where('status', 'Resolved')
                ->with('pelapor')
                ->latest('updated_at')
                ->take(10)
                ->get();

            $notifPendingUsers = Users::where('status', 'pending')
                ->latest('created_at')
                ->take(10)
                ->get();

            $notifUserClosedAdmin = Ticket::where('status', 'Closed')
                ->where('closed_by', 'user')
                ->where(function($q) { $q->where('admin_notif_user_closed_read', false)->orWhereNull('admin_notif_user_closed_read'); })
                ->with('pelapor')
                ->latest('updated_at')
                ->take(10)
                ->get();

            $view->with([
                'notifResolvedAdmin' => $notifResolvedAdmin,
                'notifPendingUsers'  => $notifPendingUsers ?? collect([]),
                'notifUserClosedAdmin'=> $notifUserClosedAdmin ?? collect([]),
                'notifCountAdmin'    => $notifResolvedAdmin->count() + ($notifPendingUsers->count() ?? 0) + ($notifUserClosedAdmin->count() ?? 0),
            ]);
        });
    }
}