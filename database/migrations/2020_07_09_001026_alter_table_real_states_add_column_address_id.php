<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRealStatesAddColumnAddressId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('real_states', function (Blueprint $table) {
            $table->unsignedInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('real_states', function (Blueprint $table) {
            $table->dropForeign('real_state_address_id_foreign');
            $table->dropColumn('address_id');
        });
    }
}
