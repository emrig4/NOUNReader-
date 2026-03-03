<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



/*
|--------------------------------------------------------------------------
| ADMIN RBAC Comands
|--------------------------------------------------------------------------
|
*/

// Assign roles
Artisan::command('rbac:assign {--superadmin}', function () {

	if( $this->option('superadmin') ){
		$userID = $this->ask('Enter user id or email?');
		$user = \App\Models\User::where('id', $userID)->orWhere('email', $userID)->first();
		$role = Bouncer::role()->where('name', 'superadmin')->first();

		if( $user == null ){
			return $this->info('User not found');
		}
		$user = $user->first();
		$this->info("{$user->name} will be assigned superadmin role ...");
		if ( $this->confirm('Do you wish to continue?') ) {
			Bouncer::assign($role)->to($user);
   			return $this->info('superadmin role assigned successfully');
		}
	}else{

		$roleName = $this->ask('Enter role name?');
		$userID = $this->ask('Enter user id or email?');
		$user = \App\Models\User::where('id', $userID)->orWhere('email', $userID)->first();
		$role = Bouncer::role()->where('name', $roleName)->first();

		if( $role == null ){
			return $this->info('Role not found');
		}
		if($user == null){
			return $this->info('User not found');
		}

		$this->info("{$user->name} will be assigned {$role->name} role on readprojecttopics");

		if ($this->confirm('Do you wish to continue?')){
			Bouncer::assign($role)->to($user);
	   		return $this->info("{$role->name} role assigned to {$user->name} successfully");
		}

	}

})->describe('Allow RBAC to be setup via console');





// Retract roles
Artisan::command('rbac:retract {--superadmin}', function () {

	if( $this->option('superadmin') ){
		$userID = $this->ask('Enter user id or email?');
		$user = \App\Models\User::where('id', $userID)->orWhere('email', $userID)->first();
		$role = Bouncer::role()->where('name', 'superadmin')->first();

		if( $user == null ){
			return $this->info('User not found');
		}
		$user = $user->first();
		$this->info("{$user->name} will be removed from superadmin role ...");
		if ( $this->confirm('Do you wish to continue?') ) {
			Bouncer::retract($role)->from($user);
   			return $this->info('superadmin role removed successfully');
		}
	}else{

		$roleName = $this->ask('Enter role name?');
		$userID = $this->ask('Enter user id or email?');
		$user = \App\Models\User::where('id', $userID)->orWhere('email', $userID)->first();
		$role = Bouncer::role()->where('name', $roleName)->first();

		if( $role == null ){
			return $this->info('Role not found');
		}
		if($user == null){
			return $this->info('User not found');
		}

		$this->info("{$user->name} will be removed from {$role->name} role on authorn");

		if ($this->confirm('Do you wish to continue?')){
			Bouncer::retract($role)->from($user);
	   		return $this->info("{$role->name} role removed from {$user->name} successfully");
		}

	}

})->describe('Allow RBAC to be setup via console');



// Seed Roles and Abilities
Artisan::command('rbac:seed', function () {
	$this->info("Admin roles and abilities will be created on readprojecttopics");
	if ($this->confirm('Do you wish to continue?')){
		
		$this->call('db:seed', ['class' => 'AbilitiesSeeder']);
		$this->call('db:seed', ['class' => 'RoleSeeder']);
   		return $this->info("roles and abilities created successfully");
	}

})->describe('Allow RBAC to be setup via console');
