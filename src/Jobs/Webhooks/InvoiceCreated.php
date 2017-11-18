<?php
/**
 * Created by PhpStorm.
 * User: larsa
 * Date: 18.11.2017
 * Time: 13:40
 */

namespace Lefamed\LaravelBillwerk\Jobs\Webhooks;


class InvoiceCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @var string
     */
    private $invoiceId;

    /**
     * @param string $invoiceId
     */
    public function __construct(string $invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }
}