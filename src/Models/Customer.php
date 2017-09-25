<?php

namespace Lefamed\LaravelBillwerk\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Lefamed\LaravelBillwerk\Jobs\SyncBillwerkCustomer;
use Lefamed\LaravelBillwerk\Transformers\Model\CustomerTransformer;

/**
 * Class Customer
 * @package Lefamed\LaravelBillwerk\Models
 */
class Customer extends Model
{
	use Notifiable;

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

	public function scopeByBillwerkId(Builder $builder, $id)
	{
		return $builder->where('billwerk_id', $id);
	}

	/**
	 * @return string
	 */
	public function routeNotificationForMail(): string
	{
		return $this->email_address;
	}

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

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function contracts()
	{
		return $this->hasMany(Contract::class);
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
			dispatch(new SyncBillwerkCustomer($customer));
		});
	}
}
