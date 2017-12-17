<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LdBwAddReferenceCodeToContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lefamed_billwerk_contracts', function (Blueprint $table) {
            $table->string('reference_code', 9)->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lefamed_billwerk_contracts', function (Blueprint $table) {
            $table->dropColumn('reference_code');
        });
    }
}
