<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorIndicatorProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quality_indicator_profiles', function (Blueprint $table) {
            $table->dropColumn('indicator_type');
            $table->dropColumn('data_collection_frequency');
            $table->dropColumn('data_collection_period');
            $table->dropColumn('data_analyst_period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quality_indicator_profiles', function (Blueprint $table) {
            $table->string('indicator_type');
            $table->string('data_collection_frequency');
            $table->string('data_collection_period');
            $table->string('data_analyst_period');
        });
    }
}
