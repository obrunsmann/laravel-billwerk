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
		'customer_name',
		'customer_sub_name',
		'company_name',
		'first_name',
		'last_name',
		'language',
		'vat_id',
		'email_address',
		'nodes'
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
		//on create, create billwerk remote customer first
		static::creating(function ($values) {
			$customerClient = new \Lefamed\LaravelBillwerk\Billwerk\Customer();
			$data = fractal($values)
				->transformWith(new CustomerTransformer())
				->toArray();
			$res = $customerClient->post($data['data'])->data();
			$values['billwerk_id'] = $res->Id;
		});
	}
}
