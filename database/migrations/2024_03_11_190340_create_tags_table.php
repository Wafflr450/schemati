<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->uuid('parent_id')->nullable();
            $table->string('name')->unique();
            $table->enum('scope', ['public_viewing', 'public_use', 'private'])->default('private');
            $table->longText('description')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });

        Schema::create('tag_admins', function (Blueprint $table) {
            $table->foreignUuid('tag_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('player_id')->constrained()->onDelete('cascade');
            $table->primary(['tag_id', 'player_id']);
            $table->timestamps();
        });

        Schema::create('tag_users', function (Blueprint $table) {
            $table->foreignUuid('tag_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('player_id')->constrained()->onDelete('cascade');
            $table->primary(['tag_id', 'player_id']);
            $table->timestamps();
        });

        Schema::create('tag_viewers', function (Blueprint $table) {
            $table->foreignUuid('tag_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('player_id')->constrained()->onDelete('cascade');
            $table->primary(['tag_id', 'player_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_dependencies');
        Schema::dropIfExists('tag_viewers');
        Schema::dropIfExists('tag_users');
        Schema::dropIfExists('tag_admins');
        Schema::dropIfExists('tags');
    }
};
