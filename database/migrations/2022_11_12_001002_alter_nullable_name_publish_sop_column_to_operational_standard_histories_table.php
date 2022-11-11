<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNullableNamePublishSopColumnToOperationalStandardHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operational_standard_histories', function (Blueprint $table) {
            $table->uuid('operational_standard_id')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('publish')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operational_standard_histories', function (Blueprint $table) {
            $table->uuid('operational_standard_id')->change();
            $table->string('name')->change();
            $table->string('publish')->change();
        });
    }
}
