<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //admin
        DB::table('users')->insert([
            //admin
            [
                'name'=>'Admin',
                'username'=>'admin',
                'email'=>'admin@gmail.com',
                'password'=>Hash::make('111'),
                'role'=>'admin',
                'status'=>'active',
            ],
            //vendor
            [
                'name'=>'Vendor',
                'username'=>'vendor',
                'email'=>'vender@gmail.com',
                'password'=>Hash::make('111'),
                'role'=>'vendor',
                'status'=>'active',
            ],
            //user
            [
                'name'=>'User',
                'username'=>'user',
                'email'=>'user@gmail.com',
                'password'=>Hash::make('111'),
                'role'=>'user',
                'status'=>'active',
            ],

        ]);
    }
}
