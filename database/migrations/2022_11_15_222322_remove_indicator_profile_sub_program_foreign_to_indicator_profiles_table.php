<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIndicatorProfileSubProgramForeignToIndicatorProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicator_profiles', function (Blueprint $table) {
            $table->dropForeign('indicator_profiles_sub_program_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicator_profiles', function (Blueprint $table) {
            $table->foreign('sub_program_id')
            ->references('id')
            ->on('sub_programs')
            ->onUpdate('cascade')->onDelete('restrict');
        });
    }
}
