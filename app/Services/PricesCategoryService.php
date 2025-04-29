<?php

namespace App\Services;

use App\Models\PricesCategory;

class PricesCategoryService
{
    // Implement your logic here
    public function createPricesCategory($categoryData){
        $priceCategory = PricesCategory::create([
            'name' => $categoryData['name'],
            'winnings' => $categoryData['winnings'],
            'campaign_id' => $categoryData['campaign_id']
        ]);
        return $priceCategory;
    }

    public function deletePriceCategory($categoryId){
        $priceCategory = PricesCategory::findOrFail($categoryId);
        $priceCategory->delete();
        return $priceCategory;
    }

    public function getAllPriceCategory(){
        $priceCategory = PricesCategory::with(['campaign'])->get();
        return $priceCategory;
    }

    public function updatePriceCategory($updateCategoryData, $categoryId) {
        $priceCategory = PricesCategory::findOrFail($categoryId);
        $filterData = array_filter($updateCategoryData);
        $priceCategory->update($filterData);
    }
}
