<?php

namespace Lefamed\LaravelBillwerk\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class TrialEndApproaching
 *
 * @package Lefamed\LaravelBillwerk\Events
 */
class TrialEndApproaching
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contract;

    /**
     * Create a new event instance.
     *
     * @param $contract
     */
    public function __construct($contract)
    {
        $this->contract = $contract;
    }
}
