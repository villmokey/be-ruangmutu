<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStatusIndicatorProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicator_profiles', function (Blueprint $table) {
            $table->integer('status')->default('0')->after('data_presentation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicator_profiles', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
