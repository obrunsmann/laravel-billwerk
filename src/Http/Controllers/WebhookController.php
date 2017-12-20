<?php

namespace Lefamed\LaravelBillwerk\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Lefamed\LaravelBillwerk\Events\ContractCancelled;
use Lefamed\LaravelBillwerk\Events\ContractChanged;
use Lefamed\LaravelBillwerk\Events\ContractCreated;
use Lefamed\LaravelBillwerk\Events\CustomerChanged;
use Lefamed\LaravelBillwerk\Events\DunningCreated;
use Lefamed\LaravelBillwerk\Events\InvoiceCorrected;
use Lefamed\LaravelBillwerk\Events\InvoiceCreated;
use Lefamed\LaravelBillwerk\Events\OrderSucceeded;
use Lefamed\LaravelBillwerk\Events\RecurringBillingApproaching;

/**
 * Class WebhookController
 */
class WebhookController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request): Response
    {
        //only allow json body
        if (!$request->isJson()) {
            abort(406, 'Please provide JSON body!');
        }

        //handle all the different events
        $content = json_decode($request->getContent());
        switch ($content->Event) {
            // Contract
            case 'ContractCreated':
                event(new ContractCreated($content->ContractId));
                break;
            case 'ContractChanged':
                event(new ContractChanged($content->ContractId));
                break;
            case 'ContractCancelled':
                event(new ContractCancelled($content->ContractId));
                break;

            // Customer
            case 'CustomerChanged':
                event(new CustomerChanged($content->CustomerId));
                break;

            // Order
            case 'OrderSucceeded':
                event(new OrderSucceeded($content->ContractId));
                break;

            // Dunning
            case 'DunningCreated':
                event(new DunningCreated($content->DunningId));
                break;

            // Invoice
            case 'InvoiceCreated':
                event(new InvoiceCreated($content->InvoiceId));
                break;
            case 'InvoiceCorrected':
                event(new InvoiceCorrected($content->OldInvoiceDraftId, $content->NewInvoiceDraftId));
                break;

            // Approaching
            case 'RecurringBillingApproaching':
                event(new RecurringBillingApproaching($content->ContractId));
                break;
            case 'TrialEndApproaching':
                event(new RecurringBillingApproaching($content->ContractId));
                break;

            #case 'PaymentEscalated':
            #    event(new \Lefamed\LaravelBillwerk\Events\PaymentEscalated($content->...));
            #    break;


        }

        return response('', 202);
    }
}
