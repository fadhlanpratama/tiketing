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
}
