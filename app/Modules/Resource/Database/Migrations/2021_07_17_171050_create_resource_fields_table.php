<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique()->index();
            $table->string('title')->nullable();
            $table->string('label')->nullable(); // eg humanities, used to store parent 
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
        Schema::dropIfExists('resource_fields');
    }
}
