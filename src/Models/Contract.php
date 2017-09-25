<?php

namespace Lefamed\LaravelBillwerk\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Contract
 * @package Lefamed\LaravelBillwerk\Models
 */
class Contract extends Model
{
	protected $table = 'lefamed_billwerk_contracts';
	public $incrementing = false;
	protected $fillable = [
		'id',
		'customer_id',
		'plan_id',
		'end_date'
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

}
