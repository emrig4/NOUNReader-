<?php

namespace App\Modules\Resource\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Resource\Models\ResourceType;


class ResourceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $types = [
        	'Thesis',
	   		'Project',
	    	'Seminar',
	    	'Book',
	    	'Paper',
	    	'Study/Lesson Note',
	    	'Curricum',
	    	'Scheme of Work',
	    	'Book Review/Summary',
	    	'Article/Essay',
	    	'Exam Questions/Answers',
	    	'Assignment',
	    	'Journal'
		];

		foreach ($types as $type) {
			ResourceType::updateOrCreate([ 'title' => $type, 'description' => '' ]);
		}
    }
}
