<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('module')->nullable()->after('content');
            $table->unsignedBigInteger('module_id')->nullable()->after('module');
            $table->json('module_options')->nullable()->after('module_id');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['module', 'module_id', 'module_options']);
        });
    }
};
