<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Inventory\Entities\InventoryImage;

class InventoryImageDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        InventoryImage::insert([
            [
                'inventory_id'=> '1',
                'image'=> '6.jpg',
                'status'=> '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'inventory_id'=> '1',
                'image'=> '7.jpg',
                'status'=> '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
