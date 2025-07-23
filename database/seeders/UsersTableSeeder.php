<?php

namespace Database\Seeders;

use App\Models\{Role, User,UserDetail};
use App\Notifications\UserNotification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Create admin detaiils
        $role = Role::where('name' , config('constants.ROLES.ADMIN'))->first();
        User::updateOrCreate([
            'email'             => "admin01@yopmail.com",
            'role_id'           => $role->id],[
            'first_name'        =>  'super',
            'last_name'         =>  'admin',
            'password'          =>   Hash::make('Admin@123'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'is_email_verified' => 1
        ]);
        $admin = User::where('email' , "admin@yopmail.com")->first();
        UserDetail::updateOrCreate(['user_id'=> $admin->id],[
            'phone_number'      => "1234578899"
        ]);

        //create customer
        $role = Role::where('name' , config('constants.ROLES.CUSTOMER'))->first();
        User::updateOrCreate([
            'email'             => "john@yopmail.com",
            'role_id'           => $role->id],[
            'first_name'        =>  'john',
            'last_name'         =>  'smith',
            'password'          =>   Hash::make('Pass@123'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'is_email_verified' => 1
            
        ]);
        $user = User::where('email' , "john@yopmail.com")->first();
        UserDetail::updateOrCreate(['user_id'=> $user->id],[
            'phone_number'      => "1234578891"
        ]);
        User::find($user->id)->notify(new UserNotification($user->full_name));

        User::updateOrCreate([
            'email'             => "Amy@yopmail.com",
            'role_id'           => $role->id],[
            'first_name'        =>  'Alison',
            'last_name'         =>  'Amy',
            'password'          =>   Hash::make('Pass@123'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'is_email_verified' => 1
        ]);
        $user = User::where('email' , "Amy@yopmail.com")->first();
        UserDetail::updateOrCreate(['user_id'=> $user->id],[
            'phone_number'      => "1234578892"
        ]);
        User::find($user->id)->notify(new UserNotification($user->full_name));

        //create users
        $role = Role::where('name' , config('constants.ROLES.DRIVER'))->first();
        User::updateOrCreate([
            'email'             => "Jake@yopmail.com",
            'role_id'           => $role->id],[
            'first_name'        =>  'Jake',
            'last_name'         =>  'Sharma',
            'password'          =>   Hash::make('Pass@123'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'is_email_verified' => 1
        ]);
        $user = User::where('email' , "Jake@yopmail.com")->first();
        UserDetail::updateOrCreate(['user_id'=> $user->id],[
            'phone_number'      => "1234578891"
        ]);
        User::find($user->id)->notify(new UserNotification($user->full_name));


    
    }
}
