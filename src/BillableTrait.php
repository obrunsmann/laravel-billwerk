<?php

namespace Lefamed\LaravelBillwerk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Lefamed\LaravelBillwerk\Jobs\DoBillwerkSignup;
use Lefamed\LaravelBillwerk\Models\Contract;
use Lefamed\LaravelBillwerk\Models\Customer;

/**
 * Trait Billable
 *
 * This trait makes an other object (like User Objekt) billable.
 *
 * @package Lefamed\LaravelBillwerk\Trait
 */
trait BillableTrait
{
	/**
	 *
	 */
	public static function bootBillableTrait()
	{
		static::created(function (Model $model) {
			dispatch(new DoBillwerkSignup($model));
		});
	}

	/**
	 * @return Customer
	 */
	public function getCustomer(): Customer
	{
		return $this->customer;
	}

	/**
	 * @return mixed
	 */
	public function customer()
	{
		return $this->hasOne(Customer::class, 'billable_id');
	}

	public function hasContract($planIds): bool
	{
		if (!is_array($planIds) && !$planIds instanceof Collection) {
			$planIds = [$planIds];
		}

		return $this->customer->contracts()->whereIn('plan_id', $planIds)->count() > 0;
	}

	/**
	 * @return array
	 */
	abstract public function getCustomerTransformation(): array;
}
