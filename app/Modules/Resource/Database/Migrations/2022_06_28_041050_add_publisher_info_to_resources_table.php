<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublisherInfoToResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->string('publisher')->nullable();
            $table->string('publisher_city')->nullable();
            $table->string('volume')->nullable();
            $table->string('issue')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('publisher');
            $table->dropColumn('publisher_city');
            $table->dropColumn('volume');
            $table->dropColumn('issue');
        });
    }
}
