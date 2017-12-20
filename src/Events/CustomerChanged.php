<?php
/**
 * Created by PhpStorm.
 * User: larsa
 * Date: 18.11.2017
 * Time: 13:40
 */

namespace Lefamed\LaravelBillwerk\Events;

/**
 * Class CustomerChanged
 *
 * @package Lefamed\LaravelBillwerk\Events
 */
class CustomerChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customerId;

    /**
     * Create a new event instance.
     *
     * @param string $customerId
     */
    public function __construct(string $customerId)
    {
        $this->customerId = $customerId;
    }
}