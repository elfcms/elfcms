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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('placeholder')->nullable();
            $table->string('description')->nullable();
            $table->string('value')->nullable();
            $table->tinyInteger('required')->nullable();
            $table->tinyInteger('disabled')->default(0);
            $table->tinyInteger('checked')->default(0);
            $table->tinyInteger('readonly')->default(0);
            $table->tinyInteger('position')->nullable();
            $table->json('attributes')->nullable();
            $table->bigInteger('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on('form_field_types')->onDelete('cascade');
            $table->bigInteger('form_id')->unsigned();
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->foreign('group_id')->references('id')->on('form_field_groups')->onDelete('set null');
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
        Schema::dropIfExists('form_fields');
    }
};
