<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Modules\Account\Models\Account;
use App\Modules\Wallet\Models\SubscriptionWallet;
use App\Modules\Wallet\Models\CreditWallet;


class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateOrCreate(
            [ 'email' => 'admin@projectandmaterials.com'],
            [
                'first_name' => 'readprojecttopics', 
                'last_name' => 'Admin', 
                'email' => 'admin@projectandmaterials.com',
                'username' => 'admin',
                'password' => \Hash::make('admin123')
            ]
        );
    
        $role = Role::updateOrCreate(['name' => 'admin']);
     
        $permissions = Permission::pluck('id','id')->all();
   
        $role->syncPermissions($permissions);
     
        $user->assignRole([$role->id]);


        Account::updateOrCreate([
            'user_id' => $user->id
        ]);

        SubscriptionWallet::updateOrCreate([
             'user_id' => $user->id,
         ]);

        CreditWallet::updateOrCreate([
             'user_id' => $user->id,
        ]);


        $user->createOrGetPaystackCustomer();


    }
}

