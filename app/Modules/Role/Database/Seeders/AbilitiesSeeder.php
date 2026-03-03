<?php
namespace App\Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Helpers\ModelUtil;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Support\Facades\DB;


class AbilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('abilities')->truncate();

        $models =ModelUtil::getModels();
        foreach ($models as $model) {
            // abilitty to perform create operation on model
        	Bouncer::ability()->create([
	            'name' => 'create' . $model,
	            'title' => 'Can Create ' . $model,
	            'description' =>
	                'Allows create operations on ' . $model,
        	]);

            // ability to perform a read operation on model
        	Bouncer::ability()->create([
	            'name' => 'read' . $model,
	            'title' => 'Can Read ' . $model,
	            'description' =>
	                'Allows read operations on ' . $model,
        	]);

            // ability to perform an update operation on model
        	Bouncer::ability()->create([
	            'name' => 'update' . $model,
	            'title' => 'Can Update ' . $model,
	            'description' =>
	                'Allows update operations on ' . $model,
        	]);

            // ability to perfomr a delete operation on model
        	Bouncer::ability()->create([
	            'name' => 'delete' . $model,
	            'title' => 'Can Delete ' . $model,
	            'description' =>
	                'Allows delete operations on ' . $model,
        	]);

            // bucket ability to perform all CRUD operations on model
            Bouncer::ability()->create([
                'name' => 'crud' . $model,
                'title' => 'Can CRUD ' . $model,
                'description' =>
                    'Allows all CRUD operations on ' . $model,
            ]);

        }

        Bouncer::ability()->create([
            'name' => 'sudo',
            'title' => 'Can do everything',
            'description' =>
                'Allows all operation',
        ]);

        Bouncer::allow('superadmin')->everything();  //for a start, allow super admin to do everything
        Bouncer::allow('superadmin')->to('sudo'); //Then bucket superadmin to sudo
    }
}
