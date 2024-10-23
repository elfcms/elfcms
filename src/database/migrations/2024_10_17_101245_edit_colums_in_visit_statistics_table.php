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
        Schema::table('visit_statistics', function (Blueprint $table) {
            $table->text('agent')->nullable()->change();
            $table->text('referer')->nullable()->change();
            $table->text('browser')->nullable()->change();
            $table->text('browser_full')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visit_statistics', function (Blueprint $table) {
            //
        });
    }
};
