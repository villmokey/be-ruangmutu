<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCustomerComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_complaints', function (Blueprint $table) {
            $table->string('complaint_id')->nullable();
            $table->date('complaint_date')->nullable();
            $table->text('follow_up')->nullable();
            $table->string('reported_by')->nullable();
            $table->text('coordination')->nullable();
            $table->date('clarification_date')->nullable();
            $table->boolean('is_public')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_complaints', function (Blueprint $table) {
            $table->dropColumn(['complaint_id', 'follow_up', 'coordination', 'clarification_date', 'is_public', 'complaint_date', 'reported_by']);
        });
    }
}
