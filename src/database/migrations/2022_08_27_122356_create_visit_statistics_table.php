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
        Schema::create('visit_statistics', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->uuid('tmp_user_uuid')->nullable();
            $table->string('uri',1200)->default('/');
            $table->string('ip',15)->nullable();
            $table->string('agent')->nullable();
            $table->string('referer',1200)->nullable();
            $table->string('platform')->nullable();
            $table->tinyInteger('mobile')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_full')->nullable();
            $table->string('method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visit_statistics');
    }
};
