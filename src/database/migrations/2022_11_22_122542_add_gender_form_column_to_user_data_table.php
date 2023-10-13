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
            $table->bigInteger('gender_form_id')->unsigned()->nullable()->after('last_name');
            $table->foreign('gender_form_id')->references('id')->on('user_gender_forms')->onDelete('set null');
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
            $table->dropForeign('user_data_gender_form_id_foreign');
            $table->dropColumn('gender_form_id');
        });
    }
};
