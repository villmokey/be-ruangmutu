<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug');
            $table->uuid('document_type_id');
            $table->uuid('program_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('document_type_id')
            ->references('id')
            ->on('document_types')
            ->onDelete('cascade');

            $table->foreign('program_id')
            ->references('id')
            ->on('programs')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_storages');
    }
}
