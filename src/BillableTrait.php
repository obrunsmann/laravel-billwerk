<?php

namespace Lefamed\LaravelBillwerk;

use Illuminate\Database\Eloquent\Model;
use Lefamed\LaravelBillwerk\Jobs\DoBillwerkSignup;
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

	public function getCustomer(): Customer
	{
		return Customer::where('billable_id', $this->id)->first();
	}

	/**
	 * @return array
	 */
	abstract public function getCustomerTransformation(): array;
}
