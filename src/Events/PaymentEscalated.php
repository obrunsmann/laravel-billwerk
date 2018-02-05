<?php
/**
 * Created by PhpStorm.
 * User: larsa
 * Date: 08.12.2017
 * Time: 21:38
 */

namespace Lefamed\LaravelBillwerk\Events;

use Carbon\Carbon;
use Lefamed\LaravelBillwerk\Billwerk\Plan;

/**
 * Class PaymentEscalated
 *
 * @package Lefamed\LaravelBillwerk\Events
 */
class PaymentEscalated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    public $contractId;

    /**
     * @var string
     */
    public $customerId;

    /**
     * @var string
     */
    public $yourCustomerId;

    /**
     * @var int
     */
    public $triggerDays;

    /**
     * @var string
     */
    public $dueDate;

    /**
     * @var string
     */
    public $paymentProvider;

    /**
     * @var string
     */
    public $paymentEscalationProcessId;


    /**
     * Create a new event instance.
     *
     * @param string $contractId
     * @param string $customerId
     * @param string $yourCustomerId
     * @param int    $triggerDays
     * @param string $dueDate
     * @param string $paymentProvider
     * @param string $paymentEscalationProcessId
     */
    public function __construct(
        string $contractId,
        string $customerId,
        string $yourCustomerId,
        int $triggerDays,
        string $dueDate,
        string $paymentProvider,
        string $paymentEscalationProcessId
    ) {
        $this->contractId                 = $contractId;
        $this->customerId                 = $customerId;
        $this->yourCustomerId             = $yourCustomerId;
        $this->triggerDays                = $triggerDays;
        $this->dueDate                    = Carbon::parse($dueDate);
        $this->paymentProvider            = $paymentProvider;
        $this->paymentEscalationProcessId = $paymentEscalationProcessId;
    }
}
//
//class PaymentEscalatedListener {
//    public function __construct() {
//        $plan = new Plan();
//        $plan = $plan->get($planId);
//        $plan->data()->Name->_c;
//    }
//}