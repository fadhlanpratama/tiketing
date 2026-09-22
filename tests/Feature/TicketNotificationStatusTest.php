<?php

namespace Tests\Feature;

use App\Models\TicketNotificationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TicketNotificationStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_notification_statuses_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('ticket_notification_statuses'));
        $this->assertTrue(method_exists(TicketNotificationStatus::class, 'forTicket'));
    }
}
