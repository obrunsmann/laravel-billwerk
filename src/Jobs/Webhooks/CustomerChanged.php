<?php

namespace Lefamed\LaravelBillwerk\Jobs\Webhooks;

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Lefamed\LaravelBillwerk\Billwerk\Customer;
use Lefamed\LaravelBillwerk\Transformers\Billwerk\CustomerTransformer;

class CustomerChanged implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	private $customerId;

	/**
	 * Create a new job instance.
	 * @param string $customerId
	 */
	public function __construct(string $customerId)
	{
		$this->customerId = $customerId;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$customerClient = new Customer();

		try {
			//fetch remote customer data
			$res = $customerClient->get($this->customerId);
			$data = fractal($res->data())
				->transformWith(new CustomerTransformer())
				->toArray()['data'];

			\Lefamed\LaravelBillwerk\Models\Customer::where('billwerk_id', $this->customerId)->update($data);
		} catch (\Exception $e) {
			Bugsnag::notifyException($e);
		}
	}
}
