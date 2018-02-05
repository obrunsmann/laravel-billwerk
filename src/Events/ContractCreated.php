<?php

namespace Lefamed\LaravelBillwerk\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Lefamed\LaravelBillwerk\Models\Contract;

/**
 * Class ContractCreated
 *
 * @package Lefamed\LaravelBillwerk\Events
 */
class ContractCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $contract;

    /**
     * Create a new event instance.
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }
}
