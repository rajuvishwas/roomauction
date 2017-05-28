<?php

namespace App\Jobs;

use App\Models\Bid;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendOutbidNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Bid
     */
    private $oldBid;

    /**
     * @var Bid
     */
    private $newBid;

    /**
     * Create a new job instance.
     *
     * @param Bid $oldBid
     * @param Bid $newBid
     */
    public function __construct(Bid $oldBid = null, Bid $newBid)
    {
        $this->oldBid = $oldBid;
        $this->newBid = $newBid;
    }

    /**
     * Send notification to Partners API,
     * when their bid is no longer a winner.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->oldBid != null
            && $this->oldBid->user_id != $this->newBid->user_id
            && $this->oldBid->user->callback_url != null) {

            $difference = $this->newBid->price - $this->oldBid->price;

            $guzzleClient = new Client();
            $guzzleClient->post(
                $this->oldBid->user->callback_url, [
                    'form_params' => [
                        'status' => 'OUTBID',
                        'current_bid' => $this->newBid->price,
                        'difference' => $difference
                    ]
                ]
            );

        }
    }
}