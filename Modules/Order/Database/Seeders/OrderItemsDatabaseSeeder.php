<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderItem;

class OrderItemsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        OrderItem::insert([
            [
                'order_id' => 1,
                'type' => 'product',
                'inventory_id' => '1',
                'combo_id' => Null,
                'quantity' => '100',
                'unit_price' => '8.5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => 2,
                'type' => 'combo',
                'inventory_id' => Null,
                'combo_id' => '1',
                'quantity' => '100',
                'unit_price' => '8.5',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
