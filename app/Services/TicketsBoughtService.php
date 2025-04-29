<?php

namespace App\Services;

use App\Models\TicketsBought;

class TicketsBoughtService
{
    // Implement your logic here

    public function getMyTicketsBought($userId){
        $tickets = TicketsBought::where("user_id", $userId)->with(['ticket', 'ticket.campaign']);
        return $tickets;
    }

    public function getUsedTicketsBought($userId) {
        $tickets = TicketsBought::where("user_id", $userId)->with(['ticket' => function($query) {
            $query->where('status', 'used');
        }, 'ticket.campaign']);
        return $tickets;
    }
    public function getUnusedTicketsBought($userId) {
        $tickets = TicketsBought::where("user_id", $userId)->with(['ticket' => function($query) {
            $query->where('status', 'unused');
        }, 'ticket.campaign']);
        return $tickets;
    }
    public function deleteTicketBought($ticketId){
        $ticketsBought = TicketsBought::where("status", "used")->findOrFail($ticketId);
        $ticketsBought->delete();
        return $ticketsBought;
    }
}
