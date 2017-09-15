<?php

namespace Lefamed\LaravelBillwerk\Models;

use Illuminate\Database\Eloquent\Model;
use Lefamed\LaravelBillwerk\Transformers\Model\CustomerTransformer;

/**
 * Class Customer
 * @package Lefamed\LaravelBillwerk\Models
 */
class Customer extends Model
{
	protected $table = 'lefamed_billwerk_customers';

	protected $fillable = [
		'billable_id',
		'customer_name',
		'customer_sub_name',
		'company_name',
		'first_name',
		'last_name',
		'language',
		'vat_id',
		'email_address',
		'nodes',

		'street',
		'house_number',
		'postal_code',
		'city',
		'country'
	];

	/**
	 * @return array
	 */
	public function toBillwerkArray(): array
	{
		return [
			'CompanyName' => $this->company_name,
			'EmailAddress' => $this->email_address
		];
	}

	protected static function boot()
	{
		// -- On Create Event -- //
		static::creating(function ($values) {
			$customerClient = new \Lefamed\LaravelBillwerk\Billwerk\Customer();
			$data = fractal($values)
				->transformWith(new CustomerTransformer())
				->toArray();
			$res = $customerClient->post($data['data'])->data();
			$values['billwerk_id'] = $res->Id;
		});

		// -- On Update Event -- //
		static::updated(function (Customer $customer) {
			$customerClient = new \Lefamed\LaravelBillwerk\Billwerk\Customer();
			$customerClient->put(
				$customer->billwerk_id,
				fractal($customer)
					->transformWith(new CustomerTransformer())
					->toArray()['data']
			);
		});
	}
}
