<?php

namespace Lefamed\LaravelBillwerk\Jobs\Webhooks;

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Lefamed\LaravelBillwerk\Billwerk\Contract;
use Lefamed\LaravelBillwerk\Models\Customer;

class ContractCancelled implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	private $contractId;

	/**
	 * Create a new job instance.
	 * @param string $contractId
	 */
	public function __construct(string $contractId)
	{
		$this->contractId = $contractId;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$contractClient = new Contract();

		try {
			//fetch the recently created contract
			$res = $contractClient->get($this->contractId)->data();

			//find corresponding customer
			$customer = Customer::where('billwerk_id', $res->CustomerId)->first();
			if (!$customer) {
				Log::info('Customer ' . $res->CustomerId . ' for contract ' . $res->Id . ' not found. Cannot apply contract.');
				return;
			}

			//get the contract
			$contract = \Lefamed\LaravelBillwerk\Models\Contract::find($res->Id);

			if (!$contract) {
				Bugsnag::notifyError('Cannot cancel contract ' + $res->Id . ' -> Contract not found', [$res, $customer->toArray()]);
				return;
			}

			//apply end date for the contract
			$contract->update([
				'end_date' => Carbon::parse($contract->EndDate)
			]);

			//trigger the event
			event(new \Lefamed\LaravelBillwerk\Events\ContractCancelled($contract));

			//send notification
			$customer->notify(new \Lefamed\LaravelBillwerk\Notifications\ContractCancelled($contract));
		} catch (\Exception $e) {
			dump($e->getMessage());
			Bugsnag::notifyException($e);
			Log::error($e->getMessage());
		}
	}
}
