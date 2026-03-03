<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccessTrackingToPurchasedResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchased_resources', function (Blueprint $table) {
            $table->timestamp('last_accessed_at')->nullable()->after('updated_at');
            $table->timestamp('access_expires_at')->nullable()->after('last_accessed_at');
            $table->index('access_expires_at', 'idx_access_expires');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchased_resources', function (Blueprint $table) {
            $table->dropIndex('idx_access_expires');
            $table->dropColumn('access_expires_at');
            $table->dropColumn('last_accessed_at');
        });
    }
}