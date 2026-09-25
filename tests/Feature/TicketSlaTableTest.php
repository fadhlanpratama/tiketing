<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\TicketSla;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TicketSlaTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_sla_table_exists_and_ticket_has_relation(): void
    {
        $this->assertTrue(Schema::hasTable('ticket_slas'));
        $this->assertTrue(method_exists(Ticket::class, 'sla'));
        $this->assertTrue(method_exists(TicketSla::class, 'ticket'));
    }

    public function test_user_cancellation_records_previous_status_and_marks_sla_as_cancelled(): void
    {
        $user = \App\Models\Users::create([
            'nama_lengkap' => 'Pelapor Batal',
            'email' => 'pelapor.batal@example.com',
            'no_telp' => '081234567899',
            'password' => 'secret123',
            'role' => 'user',
            'status' => 'active',
        ]);

        session([
            'user_id' => $user->id,
            'nama_lengkap' => $user->nama_lengkap,
        ]);

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'kategori' => 'IT—Software',
            'sub_kategori' => 'API',
            'deskripsi_masalah' => 'Tiket perlu dibatalkan',
            'prioritas' => 'Tinggi',
            'status' => 'In Progress',
            'penanggung_jawab' => 'PJ Test',
            'pj_id' => 1,
            'nomor_bmn' => 'Non-BMN',
        ]);

        $ticket->sla()->create([
            'started_at' => now()->subHours(2),
            'target_minutes' => 120,
            'elapsed_minutes' => 0,
            'status' => 'Berjalan',
        ]);

        $response = $this->delete('/user/ticket/' . $ticket->id, [
            'alasan_tutup' => 'Saya membatalkan tiket karena sudah tidak diperlukan.',
        ]);

        $response->assertRedirect('/user/dashboard');

        $ticket->refresh();

        $this->assertSame('Closed', $ticket->status);
        $this->assertSame('user', $ticket->closed_by);
        $this->assertSame('In Progress', $ticket->statusHistories()->latest('id')->first()->from_status);
        $this->assertSame('cancelled', $ticket->statusHistories()->latest('id')->first()->event_type);
        $this->assertSame('Dibatalkan', $ticket->sla()->value('status'));
    }
}
