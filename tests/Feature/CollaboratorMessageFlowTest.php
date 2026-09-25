<?php

namespace Tests\Feature;

use App\Http\Controllers\PjController;
use App\Http\Controllers\TicketController;
use App\Models\Ticket;
use App\Models\TicketCollaborator;
use App\Models\TicketMessage;
use App\Models\TicketMessageRecipient;
use App\Models\Users;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class CollaboratorMessageFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_collaborator_message_is_not_treated_as_owner_pj_message(): void
    {
        $owner = Users::create([
            'nama_lengkap' => 'PJ Utama',
            'email' => 'owner@example.com',
            'no_telp' => '081234567890',
            'password' => 'Password123',
            'role' => 'pj',
            'status' => 'active',
        ]);

        $collaborator = Users::create([
            'nama_lengkap' => 'PJ Kolab',
            'email' => 'collab@example.com',
            'no_telp' => '081234567891',
            'password' => 'Password123',
            'role' => 'pj',
            'status' => 'active',
        ]);

        $user = Users::create([
            'nama_lengkap' => 'Pelapor',
            'email' => 'user@example.com',
            'no_telp' => '081234567892',
            'password' => 'Password123',
            'role' => 'user',
            'status' => 'active',
        ]);

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'kategori' => 'IT—Software',
            'sub_kategori' => 'API',
            'deskripsi_masalah' => 'Test',
            'prioritas' => 'Tinggi',
            'status' => 'In Progress',
            'penanggung_jawab' => 'PJ Utama',
            'pj_id' => $owner->id,
            'nomor_bmn' => 'Non-BMN',
        ]);

        TicketCollaborator::create([
            'ticket_id' => $ticket->id,
            'pj_id' => $collaborator->id,
            'invited_by' => $owner->id,
            'invitation_read' => true,
            'closed_notif_read' => true,
            'created_at' => now(),
        ]);

        session(['user_id' => $collaborator->id, 'nama_lengkap' => $collaborator->nama_lengkap]);

        $request = Request::create('/pj/tickets/' . $ticket->id . '/message', 'POST', [
            'pesan' => 'Halo dari kolaborator',
        ]);

        $controller = new PjController();
        $response = $controller->storeMessage($request, (string) $ticket->id);

        $message = TicketMessage::query()->where('ticket_id', $ticket->id)->latest('id')->first();
        $recipientIds = TicketMessageRecipient::query()->where('ticket_message_id', $message->id)->pluck('user_id')->all();

        $this->assertNotNull($message);
        $this->assertSame('collaborator', $message->sender_type);
        $this->assertFalse($message->read_by_user);
        $this->assertFalse($message->read_by_pj);
        $this->assertContains($owner->id, $recipientIds);
        $this->assertContains($collaborator->id, $recipientIds);
        $this->assertTrue($message->recipients()->where('user_id', $owner->id)->first()->read === false);
        $this->assertTrue($message->recipients()->where('user_id', $collaborator->id)->first()->read === true);
    }

    public function test_user_marks_collaborator_messages_as_read_when_ticket_is_opened(): void
    {
        $owner = Users::create([
            'nama_lengkap' => 'PJ Utama',
            'email' => 'owner2@example.com',
            'no_telp' => '081234567893',
            'password' => 'Password123',
            'role' => 'pj',
            'status' => 'active',
        ]);

        $collaborator = Users::create([
            'nama_lengkap' => 'PJ Kolab',
            'email' => 'collab2@example.com',
            'no_telp' => '081234567894',
            'password' => 'Password123',
            'role' => 'pj',
            'status' => 'active',
        ]);

        $user = Users::create([
            'nama_lengkap' => 'Pelapor',
            'email' => 'user2@example.com',
            'no_telp' => '081234567895',
            'password' => 'Password123',
            'role' => 'user',
            'status' => 'active',
        ]);

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'kategori' => 'IT—Software',
            'sub_kategori' => 'API',
            'deskripsi_masalah' => 'Test',
            'prioritas' => 'Tinggi',
            'status' => 'In Progress',
            'penanggung_jawab' => 'PJ Utama',
            'pj_id' => $owner->id,
            'nomor_bmn' => 'Non-BMN',
        ]);

        TicketCollaborator::create([
            'ticket_id' => $ticket->id,
            'pj_id' => $collaborator->id,
            'invited_by' => $owner->id,
            'invitation_read' => true,
            'closed_notif_read' => true,
            'created_at' => now(),
        ]);

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'sender_type' => 'collaborator',
            'sender_nama' => $collaborator->nama_lengkap,
            'pesan' => 'Halo user, saya kolaborator',
            'foto' => null,
            'read_by_pj' => false,
            'read_by_user' => false,
        ]);

        session(['user_id' => $user->id, 'nama_lengkap' => $user->nama_lengkap]);

        $controller = new TicketController();
        $controller->show((string) $ticket->id);

        $this->assertTrue($message->refresh()->read_by_user);
    }
}
