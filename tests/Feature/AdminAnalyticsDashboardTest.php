<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\Users;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAnalyticsDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_does_not_fail_when_loading_analytics(): void
    {
        $admin = Users::create([
            'nama_lengkap' => 'Admin Test',
            'email' => 'admin.test@example.com',
            'divisi' => 'IT',
            'no_telp' => '081234567890',
            'password' => 'secret123',
            'role' => 'admin',
            'status' => 'active',
        ]);

        session(['user_id' => $admin->id]);

        $response = $this->get('/admin');

        $response->assertStatus(200);
    }

    public function test_admin_dashboard_filter_with_status_and_priority_and_completed_ticket_has_positive_cycle_time(): void
    {
        $admin = Users::create([
            'nama_lengkap' => 'Admin Filter',
            'email' => 'admin.filter@example.com',
            'divisi' => 'IT',
            'no_telp' => '081234567891',
            'password' => 'secret123',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $user = Users::create([
            'nama_lengkap' => 'Pelapor Filter',
            'email' => 'user.filter@example.com',
            'no_telp' => '081234567892',
            'password' => 'secret123',
            'role' => 'user',
            'status' => 'active',
        ]);

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'kategori' => 'IT—Software',
            'sub_kategori' => 'API',
            'deskripsi_masalah' => 'Test',
            'prioritas' => 'Tinggi',
            'status' => 'Resolved',
            'penanggung_jawab' => 'PJ',
            'pj_id' => $admin->id,
            'nomor_bmn' => 'Non-BMN',
        ]);

        $ticket->recordStatusHistory('created', null, 'Open', $user->id, 'user', now()->subDay());
        $ticket->recordStatusHistory('assigned', 'Open', 'Open', $admin->id, 'admin', now()->subHours(12));
        $ticket->recordStatusHistory('started', 'Open', 'In Progress', $admin->id, 'pj', now()->subHours(10));
        $ticket->recordStatusHistory('resolved', 'In Progress', 'Resolved', $admin->id, 'pj', now()->subHours(5));
        $ticket->recordStatusHistory('closed', 'Resolved', 'Closed', $admin->id, 'admin', now());

        session(['user_id' => $admin->id]);

        $response = $this->get('/admin?status=Resolved&prioritas=Tinggi');

        $response->assertStatus(200)
            ->assertViewHas('totalTiket', 1)
            ->assertViewHas('avgResolutionDays', fn ($value) => is_numeric($value) && (float) $value > 0);
    }
}
