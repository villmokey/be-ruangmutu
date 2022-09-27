<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTypeToIndicatorProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicator_profiles', function (Blueprint $table) {
            $table->string('type', 100)->nullable()->default('quality');
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
            $table->dropColumn(['type']);
        });
    }
}
