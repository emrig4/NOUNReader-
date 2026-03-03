<?php

namespace App\Modules\Resource\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Resource\Models\ResourceSubField;
use Illuminate\Support\Facades\File;
use App\Modules\Resource\Models\ResourceField;
use App\Modules\File\Helpers\LocalFile;



class ResourceSubFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	chdir(public_path('seeds/subfields/'));
		$subfielFiles = glob('*.txt');
    	      
        foreach ($subfielFiles as $file) {
			$content = fopen(public_path('seeds/subfields/' . $file ),'r');
	        while(!feof($content)){
	            $line = fgets($content);
	            $parent = strtok($file, ".");
	            $parentExist = ResourceField::where('slug', $parent)->first();
                $subFieldExist = ResourceSubField::where('title', 'like', '%' . $line  . '%')->first();
	            if(!$parentExist) continue;
                if($subFieldExist) continue;
	            ResourceSubField::firstOrCreate ([ 'title' => $line, 'parent_field' => $parent ]);
	            
	        }

	        fclose($content);

		}

    	// $filelist = LocalFile::getDirContents(public_path('seeds/subfields'));
    	// dd($filelist);
    }
}
