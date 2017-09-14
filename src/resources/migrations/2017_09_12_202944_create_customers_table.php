<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCustomersTable
 */
class CreateCustomersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lefamed_billwerk_customers', function (Blueprint $table) {
			$table->increments('id');
			$table->string('billwerk_id', 24)->unique();

			$table->string('customer_name')->nullable();
			$table->string('customer_sub_name')->nullable();
			$table->string('company_name')->nullable();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('language')->nullable();
			$table->string('vat_id')->nullable();
			$table->string('email_address')->nullable();
			$table->string('notes')->nullable();

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('lefamed_billwerk_customers');
	}
}
