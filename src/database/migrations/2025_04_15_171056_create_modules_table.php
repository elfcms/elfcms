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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();              // 'blog', 'core', etc.
            $table->string('title');                       // Display name
            $table->string('current_version');             // Example: 1.0.0
            $table->string('latest_version')->nullable();  // Example: 1.1.0
            $table->string('source')->nullable();          // GitHub URL or ZIP
            $table->enum('update_method', ['composer', 'zip'])->default('composer');
            $table->boolean('update_available')->default(false);
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
