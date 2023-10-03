<?php

namespace Modules\B2B\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\B2B\Entities\B2B;
use Modules\Customer\Entities\Address;

class B2BDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        B2B::insert([
            [
                'country_name' => 'Bangladesh',
                'name' => 'Md. Masudul Kbair',
                'product_name' => "Chips",
                'product_code' => "4202",
                'product_quantity' => "5000",
                'contact_number' => "01676717945",
                'email_address' => "masud.ncse@gmail.com",
                'status' => B2B::STATUS_PENDING,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
