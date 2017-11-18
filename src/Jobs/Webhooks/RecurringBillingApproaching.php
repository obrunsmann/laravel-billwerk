<?php
/**
 * Created by PhpStorm.
 * User: larsa
 * Date: 18.11.2017
 * Time: 16:36
 */

namespace Lefamed\LaravelBillwerk\Jobs\Webhooks;


class RecurringBillingApproaching implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private $contractId;

    /**
     * Create a new job instance.
     *
     * @param string $contractId
     */
    public function __construct(string $contractId)
    {
        $this->contractId = $contractId;
    }

}