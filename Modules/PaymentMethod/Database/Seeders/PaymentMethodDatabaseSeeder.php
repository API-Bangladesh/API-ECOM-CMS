<?php

namespace Modules\PaymentMethod\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\PaymentMethod\Entities\PaymentMethod;

class PaymentMethodDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        PaymentMethod::create([
            'code' => 'sslcommerze',
            'name' => 'SSL Commerze',
            'status' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
