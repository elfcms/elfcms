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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('title')->nullable();
            $table->string('action')->nullable();
            $table->string('method')->nullable();
            $table->string('enctype')->nullable();
            $table->string('email')->nullable();
            $table->string('redirect_to')->nullable();
            $table->string('submit_button')->nullable();
            $table->string('submit_name')->nullable();
            $table->string('submit_title')->nullable();
            $table->string('submit_value')->nullable();
            $table->string('reset_button')->nullable();
            $table->string('reset_title')->nullable();
            $table->string('reset_value')->nullable();
            $table->json('additional_buttons')->nullable();
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
        Schema::dropIfExists('forms');
    }
};
