<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unsigned()->index();
            $table->text('name')->nullable();
           
            $table->text('filename');
            $table->index([DB::raw('filename(220)')]);


            $table->string('location')->nullable();  // upload, external
            $table->text('external_url')->nullable();
            $table->string('disk')->nullable();
            $table->text('path')->nullable();
            $table->string('extension')->nullable();
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            
            $table->unsignedBigInteger('page_count')->nullable();

            $table->unsignedBigInteger('download')->default(0);
            $table->unsignedBigInteger('views')->default(0);
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
