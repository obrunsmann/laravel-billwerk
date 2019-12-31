<?php

namespace Lefamed\LaravelBillwerk\Http\Controllers\Api;

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Lefamed\LaravelBillwerk\Billwerk\Contract;
use Lefamed\LaravelBillwerk\Billwerk\Order;
use Lefamed\LaravelBillwerk\Http\Controllers\Controller;

/**
 * Class ContractController
 * @package Lefamed\LaravelBillwerk\Http\Controllers\Api
 */
class ContractController extends Controller
{
	/**
	 * @param $contractId
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getSelfServiceToken($contractId, Request $request)
	{
		$cacheKey = 'billwerk_contract_' . $contractId . '_token';
		$res = [];

		if (\Cache::has($cacheKey)) {
			$res = \Cache::get($cacheKey);
		} else {
			$contractService = new Contract();
			try {
				$tokenData = $contractService->selfServiceToken($contractId)->data();

				$expiry = Carbon::parse($tokenData->Expiry);
				$tokenExpireIn = $expiry->diffInMinutes(Carbon::now()) - 60;

				\Cache::put($cacheKey, $tokenData, $tokenExpireIn);

				$res = $tokenData;
			} catch (\Exception $e) {
				Bugsnag::notifyException($e);
				abort(500, 'Error while fetching token from API');
			}
		}

		return response()->json($res);
	}
}
