<?php

namespace App\Modules\Resource\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Resource\Models\ResourceField;


class ResourceFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
   //      $humanities = [
   //      	'Humanities',
			// 'History',
			// 'Languages and linguistics',
			// 'Literature',
			// 'Performing arts',
			// 'Philosophy',
			// 'Religion',
			// 'Visual arts',
   //      ];

   //      $social_sciences = [
   //      	'Social Sciences',
			// 'Anthropology',
			// 'Archaeology',
			// 'Area studies',
			// 'Cultural and ethnic studies',
			// 'Economics',
			// 'Gender and sexuality studies',
			// 'Geography',
			// 'Political science',
			// 'Psychology',
			// 'Sociology',
   //      ];

   //      $natural_sciences = [
   //      	'Natural sciences',
			// 'Space sciences',
			// 'Earth sciences',
			// 'Life sciences',
			// 'Chemistry',
			// 'Physics',
   //      ];

   //      $formal_sciences = [
   //      	'Formal sciences',
			// 'Computer sciences',
			// 'Logic',
			// 'Mathematics',
			// 'Statistics',
			// 'Systems science',
   //      ];


   //      $applied_sciences = [
			// 'Professions and Applied sciences',
			// 'Agriculture',
			// 'Architecture and design',
			// 'Business',
			// 'Divinity',
			// 'Education',
			// 'Environmental studies and Forestry',
			// 'Family and consumer science',
			// 'Health sciences',
			// 'Human physical performance and recreation',
			// 'Journalism, media studies and communication',
			// 'Law',
			// 'Library and museum studies',
			// 'Military sciences',
			// 'Public administration',
			// 'Social work',
			// 'Transportation'
   //      ];

   //      $engineering = [
   //      	'Engineering',
   //      ];


    $fields = [
    'Agriculture',
    'Art and Humanities',
    'Education',
    'Engineering',
    'Environmental and Physical Sciences',
    'Law',
    'Medical and Health Sciences',
    'Natural and Applied Sciences',
    'NOUN Resources', 
    'Project Topics',
    'Social and Management Sciences',
    'Technology',
];


		foreach ($fields as $field) {
			ResourceField::updateOrCreate([ 'title' => $field, 'label' => 'Humanities' ]);
		}
		// foreach ($social_sciences as $field) {
		// 	ResourceField::updateOrCreate([ 'title' => $field, 'label' => 'Social Sciences' ]);
		// }
		// foreach ($natural_sciences as $field) {
		// 	ResourceField::updateOrCreate([ 'title' => $field, 'label' => 'Natural Sciences' ]);
		// }
		// foreach ($formal_sciences as $field) {
		// 	ResourceField::updateOrCreate([ 'title' => $field, 'label' => 'Formal Sciences' ]);
		// }
		// foreach ($applied_sciences as $field) {
		// 	ResourceField::updateOrCreate([ 'title' => $field, 'label' => 'Applied Sciences' ]);
		// }
		// foreach ($engineering as $field) {
		// 	ResourceField::updateOrCreate([ 'title' => $field, 'label' => 'Engineering' ]);
		// }

		ResourceField::updateOrCreate([ 'title' => 'Others', 'label' => 'Others' ]);
    }
}
