<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            User::create([
                'name' => 'testLogin',
                'email' => 'test@test.com',
                'password' => bcrypt('test'),
            ]);
    }
}
