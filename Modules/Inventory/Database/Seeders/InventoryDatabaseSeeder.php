<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Inventory\Entities\Inventory;

class InventoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Inventory::insert([
            [
                'id' => '1',
                'product_id' => '21',
                'title' => 'Chips Pillow',
                'sku' => Str::random(6),
                'sale_price' => '8.5',
                'offer_price' => '7.5',
                'offer_start' => now()->subDays(7),
                'offer_end' => now()->subDays(21),
                'stock_quantity' => '5800',
                'reorder_quantity' => '50',
                'is_special_deal' => '1',
                'is_manage_stock' => '1',
                'min_order_quantity' => '10',
                'image' => '10.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '2',
                'product_id' => '21',
                'title' => 'Chips Pillow 2',
                'sku' => Str::random(6),
                'sale_price' => '9.5',
                'offer_price' => '8.5',
                'offer_start' => now()->subDays(7),
                'offer_end' => now()->subDays(21),
                'stock_quantity' => '3500',
                'reorder_quantity' => '50',
                'is_special_deal' => '1',
                'is_manage_stock' => '1',
                'min_order_quantity' => '10',
                'image' => '10.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $this->call(InventoryVariantDatabaseSeeder::class);
        $this->call(InventoryImageDatabaseSeeder::class);
    }
}
