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
        Schema::create('filestorage_storage_groups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('storage_id')->unsigned();
            $table->foreign('storage_id')->references('id')->on('filestorages')->constrained()->onDelete('cascade');
            $table->bigInteger('type_id')->unsigned()->nullable();
            $table->foreign('type_id')->references('id')->on('filestorage_filetypes')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filestorage_storage_groups');
    }
};
