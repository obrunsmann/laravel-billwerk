<?php
/**
 * Created by PhpStorm.
 * User: larsa
 * Date: 18.11.2017
 * Time: 13:40
 */

namespace Lefamed\LaravelBillwerk\Events;

/**
 * Class InvoiceCreated
 *
 * @package Lefamed\LaravelBillwerk\Events
 */
class InvoiceCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invoiceId;

    /**
     * Create a new event instance.
     *
     * @param string $invoiceId
     */
    public function __construct(string $invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }
}