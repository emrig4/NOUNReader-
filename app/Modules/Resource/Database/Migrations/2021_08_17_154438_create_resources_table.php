<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->bigIncrements('id')->index();

            $table->text('slug'); //
            $table->index([DB::raw('slug(220)')]);
            $table->unique([DB::raw('slug(220)')]);

            $table->text('title')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('type')->nullable(); // slug representing resouce_type
            $table->string('field')->nullable(); // slug on fields
            $table->string('sub_fields')->nullable(); // comma delimeted list of subfields slug
            $table->MEDIUMTEXT('overview')->nullable();
            $table->unsignedBigInteger('price')->nullable(); // use model attribute to convert to base values

            $table->string('cover')->nullable();

            $table->string('currency')->nullable();
            
            $table->unsignedBigInteger('page_count')->nullable();
            
            $table->unsignedBigInteger('preview_limit')->nullable();
            $table->string('isbn')->nullable()->index();

            $table->string('publication_year')->nullable();
            $table->unsignedBigInteger('view_count')->unsigned()->default(0);
            $table->unsignedBigInteger('read_count')->unsigned()->default(0);
            $table->unsignedBigInteger('download_count')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_private')->default(0);
            $table->boolean('is_published')->default(1);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('field')->references('slug')->on('resource_fields')->onDelete('set null');
            $table->foreign('type')->references('slug')->on('resource_types')->onDelete('set null');
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
        Schema::dropIfExists('resources');
    }
}
