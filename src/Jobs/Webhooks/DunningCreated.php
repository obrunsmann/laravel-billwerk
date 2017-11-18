<?php
/**
 * Created by PhpStorm.
 * User: larsa
 * Date: 18.11.2017
 * Time: 13:40
 */

namespace Lefamed\LaravelBillwerk\Jobs\Webhooks;


class DunningCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @var string
     */
    private $dunningId;

    /**
     * @param string $dunningId
     */
    public function __construct(string $dunningId)
    {
        $this->dunningId = $dunningId;
    }
}