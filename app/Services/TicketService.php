<?php

namespace App\Services;

use App\Models\Tickets;

class TicketService
{
    // Implement your logic here
    public function getTickets()
    {
        $tickets = Tickets::where("status", "active")
            ->where("availability", "available")
            ->with(['campaign' => function ($query) {
                $query->where('status', 'active');
            }])
            ->get();
        return $tickets;
    }

    public function deleteTicket($ticketId)
    {
        $ticket = Tickets::findOrFail($ticketId);
        $ticket->delete();
        return $ticket;
    }
}
