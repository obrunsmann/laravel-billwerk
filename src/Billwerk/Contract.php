<?php

namespace Lefamed\LaravelBillwerk\Billwerk;

use Carbon\Carbon;

/**
 * Class Contract
 *
 * @package Lefamed\LaravelBillwerk\Billwerk
 */
class Contract extends BaseClient
{
	protected $resource = 'Contracts';

	/**
	 * @param $contractId
	 * @return ApiResponse
	 */
	public function selfServiceToken($contractId): ApiResponse
	{
		return $this->get($contractId, 'SelfServiceToken');
	}

	public function subscriptions($contractId): ApiResponse
	{
		return $this->get($contractId, 'ComponentSubscriptions');
	}

	public function endComponentSubscription($subscriptionId): ApiResponse
	{
		$route = $this->baseUrl.'ComponentSubscriptions/'.$subscriptionId;
		$options = $this->buildOptions();

		$options['json'] = [
			'Id' => $subscriptionId,
			'EndDate' => Carbon::now()->toIso8601String(),
		];

		/** @noinspection PhpParamsInspection */
		return new ApiResponse($this->httpClient->put($route, $options));
	}

	/**
	 * @param $contractId
	 * @param $componentId
	 * @param $quantity
	 * @return \Lefamed\LaravelBillwerk\Billwerk\ApiResponse
	 */
	public function reportComponentSubscription($contractId, $componentId, $quantity): ApiResponse
	{
		return $this->post([
			'ComponentId' => $componentId,
			'Quantity' => $quantity,
		], null, $contractId.'/ComponentSubscriptions');
	}
}
