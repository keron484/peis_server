<?php
namespace App\Jobs;

use App\Models\Tickets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CreateTicketsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
  //  public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
   // public $retryAfter = 60;

    /**
     * The ticket number.
     *
     * @var int
     */
    public $ticketNumber;

    /**
     * The campaign ID.
     *
     * @var string
     */
    public $campaignId;

    /**
     * The ticket price.
     *
     * @var float
     */
    public $ticketPrice;

    /**
     * Create a new job instance.
     *
     * @param int $ticketNumber The number of tickets to create.
     * @param string $campaignId The ID of the campaign.
     * @param float $ticketPrice The price of each ticket.
     */
    public function __construct(int $ticketNumber, string $campaignId, float $ticketPrice)
    {
        $this->ticketNumber = $ticketNumber;
        $this->campaignId = $campaignId;
        $this->ticketPrice = $ticketPrice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            // Use a transaction for atomicity.
            DB::transaction(function () {
                $tickets = [];
                $now = now(); // Get the current timestamp once for consistency
                for ($i = 0; $i < $this->ticketNumber; $i++) {
                    $tickets[] = [
                        'id' => Str::uuid(),
                        'code' => Str::random(100),
                        'cost_price' => $this->ticketPrice,
                        'campaign_id' => $this->campaignId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                $chunks = array_chunk($tickets, 1000);
                // Chunk the insert operation to prevent memory exhaustion and improve performance.
                foreach ($chunks as $chunkedTickets) {
                    Tickets::insert($chunkedTickets);
                }
            });
        } catch (Throwable $e) {
            // Log the error.
            Log::error('Failed to create tickets: ' . $e->getMessage(), [
                'ticketNumber' => $this->ticketNumber,
                'campaignId' => $this->campaignId,
                'ticketPrice' => $this->ticketPrice,
                'exception' => $e,
            ]);

            // Throw the exception to trigger retry mechanism.
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        // Send notification to admin, or perform other failure-related tasks.
        Log::critical('CreateTicketsJob failed: ' . $exception->getMessage(), [
            'ticketNumber' => $this->ticketNumber,
            'campaignId' => $this->campaignId,
            'ticketPrice' => $this->ticketPrice,
            'exception' => $exception,
        ]);
    }
}
