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
        Schema::create('ai_detections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('original_text');
            $table->integer('word_count');
            $table->decimal('ai_score', 5, 2)->comment('AI confidence score (0-100)');
            $table->enum('confidence_level', ['low', 'medium', 'high'])->default('medium');
            $table->json('indicators')->comment('AI detection indicators found');
            $table->json('writing_style')->comment('Writing style analysis');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->decimal('detection_time', 8, 3)->comment('Time taken for detection in seconds');
            $table->string('session_id')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('ai_score');
            $table->index('confidence_level');
            $table->index('status');
        });

        Schema::create('user_ai_detection_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('check_date');
            $table->integer('checks_used')->default(0);
            $table->integer('words_used')->default(0);
            $table->timestamps();
            
            $table->unique(['user_id', 'check_date']);
            $table->index('check_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ai_detection_limits');
        Schema::dropIfExists('ai_detections');
    }
};