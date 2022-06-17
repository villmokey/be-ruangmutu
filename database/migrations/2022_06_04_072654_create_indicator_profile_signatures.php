<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicatorProfileSignatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicator_profile_signatures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('indicator_profile_id');
            $table->uuid('user_id');
            $table->integer('level');
            $table->boolean('signed')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('indicator_profile_id')
            ->references('id')
            ->on('indicator_profiles')
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
        Schema::dropIfExists('indicator_profile_signatures');
    }
}
