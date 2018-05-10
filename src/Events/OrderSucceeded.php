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
use Lefamed\LaravelBillwerk\Models\Customer;

/**
 * Class OrderSucceeded
 *
 * @package Lefamed\LaravelBillwerk\Events
 */
class OrderSucceeded
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * @var \Lefamed\LaravelBillwerk\Models\Customer
	 */
	public $customer;

	public $order;

	/**
	 * Create a new event instance.
	 */
	public function __construct(Customer $customer, $order)
	{
		$this->customer = $customer;
		$this->order = $order;
	}
}
