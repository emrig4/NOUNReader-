<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('research_topics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('department');
            $table->string('category')->nullable();
            $table->enum('type', ['project', 'thesis', 'dissertation']);
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])->default('intermediate');
            $table->boolean('is_published')->default(true);
            $table->json('tags')->nullable();
            $table->json('keywords')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('favorite_count')->default(0);
            $table->string('source', 50)->default('database'); // database, generated, ai
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Indexes for better performance
            $table->index(['department', 'type']);
            $table->index(['is_published', 'department']);
            $table->index('view_count');
            $table->index('created_at');
            $table->index('title');
            $table->index('department');
        });

        // Create a view tracking table for analytics
        Schema::create('research_topic_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_topic_id')->constrained('research_topics')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('viewed_at')->useCurrent();
            $table->string('referrer')->nullable();

            $table->index(['research_topic_id', 'viewed_at']);
            $table->index('ip_address');
            $table->index('user_id');
        });

        // Create a favorites table for user saved topics
        Schema::create('research_topic_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_topic_id')->constrained('research_topics')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->string('notes')->nullable();

            $table->unique(['research_topic_id', 'user_id']);
            $table->index('user_id');
        });

        // Create a search log table for analytics
        Schema::create('research_topic_searches', function (Blueprint $table) {
            $table->id();
            $table->string('keywords');
            $table->string('department')->nullable();
            $table->string('type')->nullable();
            $table->integer('results_count')->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('searched_at')->useCurrent();

            $table->index(['keywords', 'searched_at']);
            $table->index('department');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_topic_searches');
        Schema::dropIfExists('research_topic_favorites');
        Schema::dropIfExists('research_topic_views');
        Schema::dropIfExists('research_topics');
    }
};