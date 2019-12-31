<?php

namespace Lefamed\LaravelBillwerk\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Lefamed\LaravelBillwerk\Models\Customer;
use Lefamed\LaravelBillwerk\Transformers\Model\CustomerTransformer;

/**
 * Class SyncBillwerkCustomer
 * @package Lefamed\LaravelBillwerk\Jobs
 */
class SyncBillwerkCustomer implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	/**
	 * @var Customer
	 */
	private $customer;

	/**
	 * Create a new job instance.
	 * @param Customer $customer
	 */
	public function __construct(Customer $customer)
	{
		$this->customer = $customer;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$customerClient = new \Lefamed\LaravelBillwerk\Billwerk\Customer();
		$customerClient->put(
			$this->customer->billwerk_id,
			fractal($this->customer)
				->transformWith(new CustomerTransformer())
				->toArray()['data']
		);
	}
}
