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
        Schema::table('pages', function (Blueprint $table) {
            $table->tinyInteger('is_dynamic')->nullable()->unsigned()->default(1)->after('title');
            $table->string('path')->nullable()->after('title');
            $table->string('browser_title')->nullable()->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('is_dynamic');
            $table->dropColumn('path');
            $table->dropColumn('browser_title');
        });
    }
};
