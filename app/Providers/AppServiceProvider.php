<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\TicketCollaborator;
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
            $pjId = session('user_id');
            $namaPj = session('nama_lengkap');
            if (!$pjId) return;

            $ticketIdsPj = Ticket::where(function ($query) use ($pjId, $namaPj) {
                $query->where('pj_id', $pjId);

                if ($namaPj) {
                    $query->orWhere(function ($sub) use ($namaPj) {
                        $sub->whereNull('pj_id')
                            ->whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)]);
                    });
                }
            })
                ->pluck('id');

            $collaboratorTicketIds = TicketCollaborator::where('pj_id', $pjId)->pluck('ticket_id');

            $notifMessagesOwner = TicketMessage::whereIn('ticket_id', $ticketIdsPj)
                ->where('sender_type', '!=', 'pj')
                ->where('read_by_pj', false)
                ->get();

            $notifMessagesCollaborator = TicketMessage::whereHas('recipients', function ($query) use ($pjId) {
                $query->where('user_id', $pjId)->where('read', false);
            })->get();

            $notifMessages = $notifMessagesOwner
                ->merge($notifMessagesCollaborator)
                ->sortByDesc('created_at')
                ->take(10)
                ->values();

            $notifClosed = Ticket::whereIn('id', $ticketIdsPj)
                ->where('status', 'Closed')
                ->where('closed_by', 'user')
                ->where('pj_notif_closed_read', false)
                ->latest('updated_at')
                ->take(10)
                ->get();

            $notifClosedCollaborator = Ticket::whereIn('id', $collaboratorTicketIds)
                ->where('status', 'Closed')
                ->where('closed_by', 'user')
                ->whereHas('collaborators', function ($query) use ($pjId) {
                    $query->where('pj_id', $pjId)->where('closed_notif_read', false);
                })
                ->latest('updated_at')
                ->take(10)
                ->get();

            $notifClosed = $notifClosed->merge($notifClosedCollaborator)->sortByDesc('updated_at')->take(10)->values();

            $notifInvitations = TicketCollaborator::where('pj_id', $pjId)
                ->where('invitation_read', false)
                ->with(['ticket', 'inviter'])
                ->latest('created_at')
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
                'notifInvitations'  => $notifInvitations,
                'notifAssignedPj'    => $notifAssignedPj,
                'notifAdminClosedPj' => $notifAdminClosedPj,
                'notifCount'         => $notifMessages->count() + $notifClosed->count() + $notifInvitations->count() + $notifAssignedPj->count() + $notifAdminClosedPj->count(),
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

            $notifNewTicketsAdmin = Ticket::where('status', 'Open')
                ->where('admin_notif_new_ticket_read', false)
                ->with('pelapor')
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
                'notifNewTicketsAdmin' => $notifNewTicketsAdmin ?? collect([]),
                'notifUserClosedAdmin'=> $notifUserClosedAdmin ?? collect([]),
                'notifCountAdmin'    => $notifResolvedAdmin->count() + ($notifPendingUsers->count() ?? 0) + ($notifUserClosedAdmin->count() ?? 0) + $notifNewTicketsAdmin->count(),
            ]);
        });
    }
}