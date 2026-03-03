<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasedResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchased_resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('resource_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->integer('transaction_id')->nullable();
            $table->boolean('is_delivered')->default(0);
            $table->integer('download_count')->default(0);
            $table->timestamps();
            
            // $table->foreign('resource_id')->references('id')->on('resources')->onDelete(null);
            // $table->foreign('user_id')->references('id')->on('users')->onDelete(null);
            // $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchased_resources');
    }
}
