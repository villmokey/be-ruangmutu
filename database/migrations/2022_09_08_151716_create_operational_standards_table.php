<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationalStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operational_standards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('document_number', 100);
            $table->string('revision_number', 100);
            $table->date('released_date');
            $table->integer('page');
            $table->integer('total_page');
            $table->string('meaning');
            $table->string('goal');
            $table->string('policy');
            $table->string('reference');
            $table->string('tools');
            $table->string('procedures');
            $table->string('flow_diagram');
            $table->text('notes')->nullable();
            $table->uuid('created_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operational_standards');
    }
}
