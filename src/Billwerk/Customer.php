<?php

namespace Lefamed\LaravelBillwerk\Billwerk;

/**
 * {@inheritDoc}
 */
class Customer extends BaseClient
{
    protected $resource = 'Customers';

    public function getContracts($customerId)
    {
        return $this->get($customerId, 'Contracts');
    }
}
