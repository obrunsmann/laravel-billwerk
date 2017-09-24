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

	protected $fillable = [
		'id',
		'customer_id',
		'plan_id'
	];

}
