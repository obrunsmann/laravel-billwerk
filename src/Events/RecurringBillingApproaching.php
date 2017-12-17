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
 * Class RecurringBillingApproaching
 * @package Lefamed\LaravelBillwerk\Events
 */
class RecurringBillingApproaching
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
