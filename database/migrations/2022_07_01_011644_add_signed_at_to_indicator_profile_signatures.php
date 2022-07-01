<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignedAtToIndicatorProfileSignatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicator_profile_signatures', function (Blueprint $table) {
            $table->timestamp('signed_at')->nullable()->after('signed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicator_profile_signatures', function (Blueprint $table) {
            $table->dropColumn('signed_at');
        });
    }
}
