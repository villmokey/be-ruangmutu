<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicators', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('indicator_profiles', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicators', function (Blueprint $table) {
            $table->string('status')->default('pending');
        });

        Schema::table('indicator_profiles', function (Blueprint $table) {
            $table->string('status')->default('pending');
        });
    }
}
