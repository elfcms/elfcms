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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('code',100);
            $table->string('name');
            $table->string('theme',500)->nullable();
            $table->text('text')->nullable();
            $table->timestamp('date_from')->nullable();
            $table->timestamp('date_to')->nullable();
            $table->boolean('is_popup')->default(0);
            $table->boolean('close_remember')->default(0);
            $table->boolean('active')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
