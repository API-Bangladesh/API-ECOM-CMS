<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id'=>1,
            'name'=>'Super Admin',
            'email'=> 'admin@mail.com',
            'mobile_no'=>'01521225987',
            'gender'=>1,
            'password' => 'Admin@100%'
        ]);
    }
}
