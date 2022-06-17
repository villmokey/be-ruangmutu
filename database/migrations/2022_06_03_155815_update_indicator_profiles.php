<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIndicatorProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicator_profiles', function (Blueprint $table) {
            $table->dropColumn('quality_dimension');
            $table->dropColumn('inclusion_criteria');
            $table->dropColumn('exclusion_criteria');
            $table->dropColumn('data_collection_instrument');

            $table->integer('achievement_target')->after('denominator');
            $table->text('criteria')->after('achievement_target');

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
            $table->string('quality_dimension');
            $table->text('inclusion_criteria');
            $table->text('exclusion_criteria');
            $table->string('data_collection_instrument');

            $table->dropColumn('achievement_target');
            $table->dropColumn('criteria');
        });
    }
}
