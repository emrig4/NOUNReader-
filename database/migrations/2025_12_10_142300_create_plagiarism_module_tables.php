<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlagiarismModuleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plagiarism_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('original_text');
            $table->integer('word_count');
            $table->float('plagiarism_score')->default(0);
            $table->json('sources')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
            $table->decimal('check_time', 8, 2)->default(0);
            $table->string('session_id')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('session_id');
        });

        Schema::create('user_plagiarism_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('check_date');
            $table->integer('checks_used')->default(0);
            $table->integer('words_used')->default(0);
            $table->timestamps();
            
            $table->unique(['user_id', 'check_date']);
            $table->index(['user_id', 'check_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_plagiarism_limits');
        Schema::dropIfExists('plagiarism_checks');
    }
}
