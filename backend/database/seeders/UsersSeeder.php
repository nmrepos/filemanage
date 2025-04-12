<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'id' => (string) Str::uuid(),
            'firstName' => 'John',
            'lastName' => 'Doe',
            'isDeleted' => false,
            'userName' => 'johndoe',
            'normalizedUserName' => strtoupper('johndoe'),
            'email' => 'test@test.com',
            'normalizedEmail' => strtoupper('test@test.com'),
            'emailConfirmed' => true,
            'password' => Hash::make('test'),
            'securityStamp' => Str::random(10),
            'concurrencyStamp' => Str::random(10),
            'phoneNumber' => '1234567890',
            'phoneNumberConfirmed' => false,
            'twoFactorEnabled' => false,
            'lockoutEnd' => null,
            'lockoutEnabled' => true,
            'accessFailedCount' => 0,
        ]);
    }
}
