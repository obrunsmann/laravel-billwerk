<?php
/**
 * Created by PhpStorm.
 * User: larsa
 * Date: 18.11.2017
 * Time: 13:40
 */

namespace Lefamed\LaravelBillwerk\Events;

/**
 * Class InvoiceCorrected
 *
 * @package Lefamed\LaravelBillwerk\Events
 */
class InvoiceCorrected
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    public $oldInvoiceDraftId;

    /**
     * @var string
     */
    public $newInvoiceDraftId;

    /**
     * Create a new event instance.
     *
     * @param string $oldInvoiceDraftId
     * @param string $newInvoiceDraftId
     */
    public function __construct(string $oldInvoiceDraftId, string $newInvoiceDraftId)
    {
        $this->oldInvoiceDraftId = $oldInvoiceDraftId;
        $this->newInvoiceDraftId = $newInvoiceDraftId;
    }
}