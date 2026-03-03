<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_reports', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('resource_id')->nullable();
            $table->foreignId('user_id')->nullable();

            $table->string('name')->nullable();
            $table->MEDIUMTEXT('comment')->nullable();

            $table->foreign('resource_id')->references('id')->on('resources')->onDelete(null);
            $table->foreign('user_id')->references('id')->on('users')->onDelete(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_reports');
    }
}
