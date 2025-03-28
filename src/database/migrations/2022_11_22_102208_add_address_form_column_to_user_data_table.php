<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_data', function (Blueprint $table) {
            $table->bigInteger('address_form_id')->unsigned()->nullable()->after('user_id');
            $table->foreign('address_form_id')->references('id')->on('user_address_forms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_data', function (Blueprint $table) {
            $table->dropForeign('user_data_address_form_id_foreign');
            $table->dropColumn('address_form_id');
        });
    }
};
