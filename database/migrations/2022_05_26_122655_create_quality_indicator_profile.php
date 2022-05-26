<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualityIndicatorProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quality_indicator_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('program_id');
            $table->uuid('sub_program_id');
            $table->string('title');
            $table->text('indicator_selection_based');
            $table->string('quality_dimension');
            $table->string('objective');
            $table->text('operational_definition');
            $table->string('indicator_type');
            $table->string('measurement_status');
            $table->text('numerator');
            $table->text('denominator');
            $table->text('inclusion_criteria');
            $table->text('exclusion_criteria');
            $table->text('measurement_formula');
            $table->string('data_collection_design');
            $table->string('data_source');
            $table->string('population');
            $table->string('data_collection_frequency');
            $table->string('data_collection_period');
            $table->string('data_analyst_period');
            $table->string('data_presentation');
            $table->string('data_collection_instrument');
            $table->bigInteger('pic_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('program_id')
            ->references('id')
            ->on('programs')
            ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('sub_program_id')
            ->references('id')
            ->on('sub_programs')
            ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('pic_id')
            ->references('id')
            ->on('users')
            ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quality_indicator_profiles');
    }
}
