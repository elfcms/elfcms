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
        Schema::table('filestorage_files', function (Blueprint $table) {
            $table->boolean('active')->default(true)->after('description');
            $table->integer('position')->default(0)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filestorage_files', function (Blueprint $table) {
            $table->dropColumn('active');
            $table->dropColumn('position');
        });
    }
};
