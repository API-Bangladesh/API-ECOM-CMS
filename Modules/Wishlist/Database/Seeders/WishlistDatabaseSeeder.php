<?php

namespace Modules\Wishlist\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Wishlist\Entities\Wishlist;

class WishlistDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Wishlist::insert([
            [
                'customer_id' => '1',
                'inventory_id' => '1',
                'combo_id' => Null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_id' => '1',
                'inventory_id' => Null,
                'combo_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
