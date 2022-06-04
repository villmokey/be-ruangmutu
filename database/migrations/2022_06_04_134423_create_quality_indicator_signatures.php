<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualityIndicatorSignatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quality_indicator_signatures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('indicator_id');
            $table->bigInteger('user_id')->unsigned();
            $table->integer('level');
            $table->boolean('signed')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('indicator_id')
            ->references('id')
            ->on('quality_indicators')
            ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('user_id')
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
        Schema::dropIfExists('quality_indicator_signatures');
    }
}
