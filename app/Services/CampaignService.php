<?php

namespace App\Services;

use App\Jobs\CreateTicketsJob;
use App\Models\Campaign;
use Exception;
use illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class CampaignService
{
       /**
     * Creates a new campaign.
     *
     * @param array $campaignData The data for the new campaign.
     *
     * @return Campaign The newly created campaign.
     *
     * @throws Exception If an error occurs during campaign creation.
     */
    public function createCampaign(array $campaignData): Campaign
    {
        try {
            $randomId = Str::uuid();
            $campaign = Campaign::create([
                  'id' => $randomId,
                  'name' => $campaignData['name'],
                  'description' => $campaignData['description'],
                  'start_date' => $campaignData['start_date'],
                  'end_date' => $campaignData['end_date'],
                  'status' => $campaignData['status'],
                  'total_tickets' => $campaignData['total_tickets'],
                  'ticket_price' => $campaignData['ticket_price'],
                  'minimum_winnings' => $campaignData['minimum_winnings']
            ]);
            dispatch(new CreateTicketsJob($campaignData['total_tickets'], $randomId, $campaignData['ticket_price']));
            return $campaign;
        } catch (Exception $e) {
            throw new Exception('Failed to create campaign: ' . $e->getMessage(), 0, $e); // Wrap the error
        }
    }

    /**
     * Retrieves all campaigns.
     *
     * @return \Illuminate\Database\Eloquent\Collection<Campaign> A collection of all campaigns.
     */
    public function getCampaigns()
    {
        try {
            return Campaign::all();
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve campaigns: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Updates an existing campaign.
     *
     * @param array $updateCampaignData The updated data for the campaign.
     * @param string $campaignId The ID of the campaign to update.
     *
     * @return Campaign The updated campaign.
     *
     * @throws ModelNotFoundException If the campaign with the given ID is not found.
     * @throws Exception If an error occurs during the update process.
     */
    public function updateCampaign(array $updateCampaignData, string $campaignId): Campaign
    {

        try {
            $campaign = Campaign::findOrFail($campaignId);
            $campaign->fill($updateCampaignData);
            $campaign->save();

            return $campaign;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception('Failed to update campaign: ' . $e->getMessage(), 0, $e); //wrap
        }
    }

    /**
     * Deletes a campaign.
     *
     * @param string $campaignId The ID of the campaign to delete.
     *
     * @throws ModelNotFoundException If the campaign with the given ID is not found.
     * @throws Exception If an error occurs during deletion.
     */
    public function deleteCampaign(string $campaignId): void
    {
        try {
            $campaign = Campaign::findOrFail($campaignId);
            $campaign->delete();
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception('Failed to delete campaign: ' . $e->getMessage(), 0, $e); //wrap
        }
    }

    /**
     * Activates a campaign.
     *
     * @param int $campaignId The ID of the campaign to activate.
     *
     * @return Campaign The activated campaign.
     *
     * @throws ModelNotFoundException If the campaign with the given ID is not found.
     * @throws Exception If an error occurs during activation.
     */
    public function activateCampaign(string $campaignId): Campaign
    {
        try {
            $campaign = Campaign::findOrFail($campaignId);
            $campaign->status = "active";
            $campaign->save();
            return $campaign;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception('Failed to activate campaign: ' . $e->getMessage(), 0, $e); //wrap
        }
    }

    /**
     * Deactivates a campaign.
     *
     * @param int $campaignId The ID of the campaign to deactivate.
     *
     * @return Campaign The deactivated campaign.
     *
     * @throws ModelNotFoundException If the campaign with the given ID is not found.
     * @throws Exception If an error occurs during deactivation.
     */
    public function deactivateCampaign(string $campaignId): Campaign
    {
        try {
            $campaign = Campaign::findOrFail($campaignId);
            $campaign->status = "inactive";
            $campaign->save();
             return $campaign;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception('Failed to deactivate campaign: ' . $e->getMessage(), 0, $e); //wrap
        }
    }
}
