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
        Schema::table('cookie_settings', function (Blueprint $table) {
            $table->integer('cookie_lifetime')->unsigned()->nullable()->after('active');
            $table->string('privacy_path')->nullable()->after('active');
            $table->text('text')->nullable()->after('active');
            $table->boolean('use_default_text')->nullable()->default(1)->after('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cookie_settings', function (Blueprint $table) {
            $table->dropColumn('privacy_path');
            $table->dropColumn('text');
            $table->dropColumn('use_default_text');
            $table->dropColumn('cookie_lifetime');
        });
    }
};
