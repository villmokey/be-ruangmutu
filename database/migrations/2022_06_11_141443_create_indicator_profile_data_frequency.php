<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicatorProfileDataFrequency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicator_profile_data_frequencies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('indicator_profile_id');
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('indicator_profile_id')
            ->references('id')
            ->on('indicator_profiles')
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
        Schema::dropIfExists('indicator_profile_data_frequencies');
    }
}
