<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // অ্যাডমিন ইউজার তৈরি
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'mobile' => '01835061968',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'mobile' => '01735061968',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Sales',
            'email' => 'sales@gmail.com',
            'mobile' => '01335061968',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // সাধারণ কাস্টমার ইউজার তৈরি
        User::create([
            'name' => 'Customer',
            'email' => 'customer@gmail.com',
            'mobile' => '01303132067',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
        User::create([
            'name' => 'Asik Bhuyain',
            'email' => 'asik@gmail.com',
            'mobile' => '01703132067',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
        User::create([
            'name' => 'ONTO',
            'email' => 'onto@gmail.com',
            'mobile' => '01803132067',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}
