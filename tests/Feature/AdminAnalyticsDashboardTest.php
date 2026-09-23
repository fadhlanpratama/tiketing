<?php

namespace Tests\Feature;

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
}
