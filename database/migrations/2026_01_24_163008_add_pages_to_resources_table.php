<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPagesToResourcesTable extends Migration
{
    public function up()
    {
        Schema::table('resources', function (Blueprint $table) {
            // Add 'pages' column if it doesn't exist
            if (!Schema::hasColumn('resources', 'pages')) {
                $table->integer('pages')->nullable()->comment('Number of pages in PDF');
            }
        });
    }

    public function down()
    {
        Schema::table('resources', function (Blueprint $table) {
            if (Schema::hasColumn('resources', 'pages')) {
                $table->dropColumn('pages');
            }
        });
    }
}