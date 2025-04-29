<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCampaignRequest;
use App\Services\ApiResponseService;
use App\Services\CampaignService;
use Exception;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
   protected CampaignService $campaignService;
   public function __construct(CampaignService $campaignService){
       $this->campaignService = $campaignService;
   }

   public function createCampaign(CreateCampaignRequest $request){
       try{
           $createCampaign = $this->campaignService->createCampaign($request->validated());
           return ApiResponseService::success("Campaign created successfully", $createCampaign, null, 201);
       }
       catch(Exception $e){
          return ApiResponseService::error($e->getMessage(), null, 500);
       }
   }
}
