<?php

namespace App\Services;

use App\Models\GrandPriceWinners;

class GrandPriceWinnersService
{
    // Implement your logic here
    public function getGrandPriceWinners(){
        return GrandPriceWinners::with(['campaign', 'user'])->get();
    }

    public function deleteGrandPriceWinners($id){
        $grandPriceWinners = GrandPriceWinners::findOrFail($id);
        $grandPriceWinners->delete();
    }
}
