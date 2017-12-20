<?php
/**
 * Created by PhpStorm.
 * User: larsa
 * Date: 18.11.2017
 * Time: 13:40
 */

namespace Lefamed\LaravelBillwerk\Events;

/**
 * Class DunningCreated
 *
 * @package Lefamed\LaravelBillwerk\Events
 */
class DunningCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $dunningId;

    /**
     * Create a new event instance.
     *
     * @param string $dunningId
     */
    public function __construct(string $dunningId)
    {
        $this->dunningId = $dunningId;
    }
}