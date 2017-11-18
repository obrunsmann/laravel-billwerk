<?php

namespace Lefamed\LaravelBillwerk\Billwerk;

/**
 * {@inheritDoc}
 */
class Contract extends BaseClient
{
    protected $resource = 'Contracts';

    /**
     * @param $contractId
     *
     * @return ApiResponse
     */
    public function selfServiceToken($contractId)
    {
        return $this->get($contractId, 'SelfServiceToken');
    }
}
