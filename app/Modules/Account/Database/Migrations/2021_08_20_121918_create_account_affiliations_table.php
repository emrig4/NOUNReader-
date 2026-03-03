<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountAffiliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_affiliations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();

            $table->string('position')->nullable(); // position slug here
            $table->string('department')->nullable(); // subfield slug here
            $table->string('institution')->nullable(); // institution slug here
            $table->string('profile')->nullable(); // brief summary here
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->boolean('is_current')->default(0);
            $table->nullableTimestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_affiliations');
    }
}
