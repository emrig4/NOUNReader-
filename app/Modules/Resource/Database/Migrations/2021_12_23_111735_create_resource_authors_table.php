<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_authors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->nullable()->index();
            $table->string('fullname',)->nullable();
           
            $table->foreignId('resource_id')->nullable();
            $table->boolean('is_lead')->default(0);
            $table->nullableTimestamps();

            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_authors');
    }
}
