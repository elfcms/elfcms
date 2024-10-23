<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_address_forms', function (Blueprint $table) {
            $table->text('lang_string')->nullable()->change();
            $table->string('form',512)->nullable()->change();
            $table->string('full_form',512)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_address_forms', function (Blueprint $table) {
            //
        });
    }
};
