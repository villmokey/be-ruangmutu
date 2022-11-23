<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsGeneratedColumnToIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicators', function (Blueprint $table) {
            $table->boolean('is_generated')->default(false);
        });
        
        Schema::table('indicator_profiles', function (Blueprint $table) {
            $table->boolean('is_generated')->default(false);
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
            $table->dropColumn('is_generated');
        });
        
        Schema::table('indicators', function (Blueprint $table) {
            $table->dropColumn('is_generated');
        });
    }
}
