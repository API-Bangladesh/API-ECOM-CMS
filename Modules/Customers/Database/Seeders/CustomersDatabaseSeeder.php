<?php

namespace Modules\Customers\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Modules\Customers\Entities\Customers;

class CustomersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Customers::insert([
            [
                'id'=> '1',
                'name'=> 'Customer',
                'email'=> 'customer@demo.com',
                'password'=> Hash::make('123456'),
                'address'=> 'Dhaka, Bangladesh',
                'date_of_birth'=> now()->subYears(33),
                'gender'=> '1',
                'phone_number'=> '01676717945',
                'image'=> 'customer.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // $this->call("OthersTableSeeder");
    }
}
