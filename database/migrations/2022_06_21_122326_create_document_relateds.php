<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentRelateds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_relateds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('document_id');
            $table->uuid('related_document_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('document_id')
            ->references('id')
            ->on('documents')
            ->onDelete('cascade');

            $table->foreign('related_document_id')
            ->references('id')
            ->on('documents')
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
        Schema::dropIfExists('document_storage_relateds');
    }
}
