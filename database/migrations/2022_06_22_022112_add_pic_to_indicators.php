<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPicToIndicators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicators', function (Blueprint $table) {
            $table->dropColumn('quality_goal_id');
            $table->uuid('first_pic_id')->after('next_plan')->nullable();
            $table->uuid('second_pic_id')->after('first_pic_id')->nullable();
            $table->uuid('assign_by')->after('second_pic_id')->nullable();
            $table->string('created_by')->after('assign_by')->nullable();
            $table->uuid('title')->after('id');
            $table->string('quality_goal')->after('month');

            $table->foreign('first_pic_id')->references('id')->on('users');
            $table->foreign('second_pic_id')->references('id')->on('users');
            $table->foreign('assign_by')->references('id')->on('users');
            $table->foreign('title')->references('id')->on('indicator_profiles');
            $table->foreign('program_id')->references('id')->on('programs');
            $table->foreign('sub_program_id')->references('id')->on('sub_programs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicators', function (Blueprint $table) {
            $table->dropColumn('first_pic_id');
            $table->dropColumn('second_pic_id');
            $table->dropColumn('assign_by');
            $table->dropColumn('created_by');
            $table->dropColumn('title');
            $table->dropColumn('quality_goal');
            $table->uuid('quality_goal_id');
        });
    }
}
