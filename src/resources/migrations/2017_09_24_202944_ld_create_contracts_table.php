<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class LdCreateContractsTable
 */
class LdCreateContractsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lefamed_billwerk_contracts', function (Blueprint $table) {
			$table->string('id', 24)->primary();
			$table->unsignedInteger('customer_id');
			$table->string('plan_id', 24);

			$table->dateTime('end_date')->nullable();
			$table->timestamps();

			$table->foreign('customer_id')
				->references('id')
				->on('lefamed_billwerk_customers')
				->onDelete('CASCADE');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('lefamed_billwerk_contracts');
	}
}
