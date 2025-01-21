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
        Schema::create('filestorage_files', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('path');
            $table->string('extension')->nullable();
            $table->string('download_name')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('link_title')->nullable();
            $table->string('link')->nullable();
            $table->string('mimetype');
            $table->integer('size');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('length')->nullable();
            $table->integer('bitrate')->nullable();
            $table->integer('fps')->nullable();
            $table->string('quality')->nullable();
            $table->bigInteger('storage_id')->unsigned();
            $table->foreign('storage_id')->references('id')->on('filestorages')->constrained()->onDelete('cascade');
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->foreign('group_id')->references('id')->on('filestorage_filegroups')->constrained()->onDelete('set null');
            $table->bigInteger('type_id')->unsigned()->nullable();
            $table->foreign('type_id')->references('id')->on('filestorage_filetypes')->constrained()->onDelete('set null');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filestorage_files');
    }
};
