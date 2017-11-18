<?php

namespace Lefamed\LaravelBillwerk\Jobs\Webhooks;

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Lefamed\LaravelBillwerk\Billwerk\Order;
use Lefamed\LaravelBillwerk\Models\Customer;

/**
 * Class OrderSucceeded
 * Send order confirmation to the user.
 *
 * @package Lefamed\LaravelBillwerk\Jobs\Webhooks
 */
class OrderSucceeded implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private $contractId;

    /**
     * @var string
     */
    private $orderId;

    /**
     * Create a new job instance.
     *
     * @param string $contractId
     * @param string $orderId
     */
    public function __construct(string $contractId, string $orderId)
    {
        $this->contractId = $contractId;
        $this->orderId    = $orderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $orderClient = new Order();

            //get order details
            $order = $orderClient->get($this->orderId)->data();

            //search the customer
            $customer = Customer::byBillwerkId($order->CustomerId)->first();
            if ($customer) {
                $customer->notify(new \Lefamed\LaravelBillwerk\Notifications\OrderSucceeded($order));
            }
        } catch (\Exception $e) {
            dump($e);
            Bugsnag::notifyException($e);
            Log::error($e->getMessage());
        }
    }
}
