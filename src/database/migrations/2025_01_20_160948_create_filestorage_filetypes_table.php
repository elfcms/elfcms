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
        Schema::create('filestorage_filetypes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->foreign('group_id')->references('id')->on('filestorage_filegroups')->constrained()->onDelete('set null');
            $table->string('description')->nullable();
            $table->string('mime_prefix')->nullable();
            $table->string('mime_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filestorage_filetypes');
    }
};
