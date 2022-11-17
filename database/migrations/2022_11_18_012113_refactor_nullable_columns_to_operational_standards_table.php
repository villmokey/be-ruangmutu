<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorNullableColumnsToOperationalStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operational_standards', function (Blueprint $table) {
            $table->text('meaning')->change();
            $table->text('goal')->change();
            $table->text('policy')->change();
            $table->text('reference')->change();
            $table->text('tools')->change();
            $table->text('procedures')->change();
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
            $table->string('meaning')->change();
            $table->string('goal')->change();
            $table->string('policy')->change();
            $table->string('reference')->change();
            $table->string('tools')->change();
            $table->string('procedures')->change();
        });
    }
}
