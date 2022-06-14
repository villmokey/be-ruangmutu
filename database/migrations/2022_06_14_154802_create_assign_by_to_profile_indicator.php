<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignByToProfileIndicator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quality_indicator_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('assign_by')->after('pic_id')->nullable();

            $table->foreign('assign_by')
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
        Schema::table('quality_indicator_profiles', function (Blueprint $table) {
            $table->dropColumn('assign_by');
        });
    }
}
