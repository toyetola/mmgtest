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
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['id'=>1, 'firstname' => 'Bolu', 'lastname' => 'leke', 'email' => 'stephan-v@gmail.com', 'password' => Hash::make('mypassword')],
            ['id'=>2, 'firstname' => 'Alli', 'lastname' => 'Ahmad', 'email' => 'aliahm@gmail.com', 'password' => Hash::make('mypassword')],
            ['id'=>3, 'firstname' => 'Admin', 'lastname' => 'Don', 'email' => 'admin@@gmail.com', 'password' => Hash::make('password01'), 'role'=>'admin'],
        ];

        foreach($users as $user) {
            User::create($user);
        }
    }
}
