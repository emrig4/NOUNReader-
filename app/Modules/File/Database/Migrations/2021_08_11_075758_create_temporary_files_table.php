<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryFilesTable extends Migration
{
    public function up()
    {
        Schema::create('temporary_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('session_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->text('filename');
            $table->index([DB::raw('filename(220)')]);

            $table->string('disk')->nullable();
            $table->string('status')->nullable();
            $table->text('path')->nullable();
            $table->string('extension')->nullable();
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('label')->nullable(); // converted, original, sample
            $table->nullableTimestamps();
        });
    }
}