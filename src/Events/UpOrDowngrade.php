<?php

namespace Lefamed\LaravelBillwerk\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Lefamed\LaravelBillwerk\Models\Contract;

/**
 * Class UpOrDowngrade
 *
 * @package Lefamed\LaravelBillwerk\Events
 */
class UpOrDowngrade
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $contract;

	/**
	 * Create a new event instance.
	 *
	 * @param \Lefamed\LaravelBillwerk\Models\Contract $contract
	 */
	public function __construct(Contract $contract)
	{
		$this->contract = $contract;
	}
}
