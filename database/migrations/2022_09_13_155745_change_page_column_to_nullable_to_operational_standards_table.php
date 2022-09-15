<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePageColumnToNullableToOperationalStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operational_standards', function (Blueprint $table) {
            $table->integer('page')->nullable()->change();
            $table->integer('total_page')->nullable()->change();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operational_standards', function (Blueprint $table) {
            $table->integer('page')->nullable(false)->change();
            $table->integer('total_page')->nullable(false)->change();
        });
    }
}
