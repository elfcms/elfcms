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
        Schema::create('form_field_options', function (Blueprint $table) {
            $table->id();
            $table->string('value')->nullable();
            $table->string('text')->nullable();
            $table->tinyInteger('selected')->default(0);
            $table->tinyInteger('disabled')->default(0);
            $table->bigInteger('field_id')->unsigned();
            $table->foreign('field_id')->references('id')->on('form_fields')->onDelete('cascade');
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
        Schema::dropIfExists('form_field_options');
    }
};
