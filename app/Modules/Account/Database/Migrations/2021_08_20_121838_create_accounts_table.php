<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index()->unique();
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->text('interest')->nullable();  // comma delimited list of interest from
            $table->MEDIUMTEXT('biography')->nullable();

            // contact
            $table->string('phone')->nullable();
            $table->string('phone1')->nullable();
            $table->text('address')->nullable();

            // social links
            $table->string('google')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();

            // Bank links
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->boolean('bank_verified')->dafault(1)->nullable();
            

            $table->boolean('status')->dafault(1)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('accounts');
    }
}
