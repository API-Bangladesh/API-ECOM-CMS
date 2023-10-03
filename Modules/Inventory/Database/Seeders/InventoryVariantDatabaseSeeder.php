<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Inventory\Entities\InventoryImage;
use Modules\Inventory\Entities\InventoryVariant;

class InventoryVariantDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        InventoryVariant::insert([
            [
                'inventory_id'=> '1',
                'variant_id'=> '6',
                'variant_option_id'=> '4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'inventory_id'=> '1',
                'variant_id'=> '8',
                'variant_option_id'=> '5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'inventory_id'=> '2',
                'variant_id'=> '8',
                'variant_option_id'=> '19',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
