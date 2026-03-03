<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceSubFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_sub_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->index();
            $table->string('title')->nullable();
            $table->string('parent_field')->nullable();
            
            $table->foreign('parent_field')->references('slug')->on('resource_fields')->onDelete('set null');
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
        Schema::dropIfExists('resource_sub_fields');
    }
}
