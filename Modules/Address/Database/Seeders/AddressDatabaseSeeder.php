<?php

namespace Modules\Address\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Address\Entities\Address;

class AddressDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Address::insert([
            [
                'title'=>'Home',
                'name'=>'Md. Masudul Kabir',
                'address_line_1'=> '43 Nawabpur',
                'address_line_2'=> 'Nobendro Nath',
                'division_id'=> '6',
                'district_id'=> '6',
                'upazila_id' => '6',
                'postcode' => '12345',
                'phone' => '01676717945',
                'email' => 'masud.ncse@gmail.com',
                'customer_id' => '1',
                'is_default_billing' => '1',
                'is_default_shipping' => Null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'=>'Office',
                'name'=>'Rashed Sikder',
                'address_line_1'=> '43 Nawabpur',
                'address_line_2'=> 'Nobendro Nath',
                'division_id'=> '6',
                'district_id'=> '6',
                'upazila_id' => '6',
                'postcode' => '12345',
                'phone' => '01676717945',
                'email' => 'rashed.sikder@gmail.com',
                'customer_id' => '1',
                'is_default_billing' => Null,
                'is_default_shipping' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

    }
}
