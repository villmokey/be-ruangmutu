<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubPrograms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_programs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('program_id');
            $table->string('name');
            $table->boolean('is_publish')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('program_id')
            ->references('id')
            ->on('programs')
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
        Schema::dropIfExists('sub_programs');
    }
}
