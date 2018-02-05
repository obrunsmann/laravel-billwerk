<?php
/**
 * Created by PhpStorm.
 * User: larsa
 * Date: 18.11.2017
 * Time: 13:40
 */

namespace Lefamed\LaravelBillwerk\Jobs\Webhooks;


class InvoiceCorrected implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @var string
     */
    private $oldInvoiceDraftId;

    /**
     * @var string
     */
    private $newInvoiceDraftId;

    /**
     * @param string $oldInvoiceDraftId
     * @param string $newInvoiceDraftId
     *
     * @internal param string $invoiceId
     */
    public function __construct(string $oldInvoiceDraftId, string $newInvoiceDraftId)
    {
        $this->oldInvoiceDraftId = $oldInvoiceDraftId;
        $this->newInvoiceDraftId = $newInvoiceDraftId;
    }
}