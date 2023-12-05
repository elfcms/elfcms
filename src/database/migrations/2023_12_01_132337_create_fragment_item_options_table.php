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
        Schema::create('fragment_item_options', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('fragment_items')->constrained()->onDelete('cascade');
            $table->bigInteger('data_type_id')->unsigned();
            $table->foreign('data_type_id')->references('id')->on('data_types')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('value')->nullable();
            $table->bigInteger('value_int')->nullable();
            $table->float('value_float')->nullable();
            $table->date('value_date')->nullable();
            $table->dateTime('value_datetime')->nullable();
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
        Schema::dropIfExists('fragment_item_options');
    }
};
