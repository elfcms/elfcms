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
        Schema::create('elfcms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name')->nullable();
            $table->json('params')->nullable();
            $table->text('value')->nullable();
            $table->string('description_code')->nullable();
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
        Schema::dropIfExists('elfcms_settings');
    }
};
