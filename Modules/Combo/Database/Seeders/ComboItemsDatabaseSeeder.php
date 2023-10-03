<?php

namespace Modules\Combo\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Combo\Entities\ComboImage;
use Modules\Combo\Entities\ComboItem;

class ComboItemsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        ComboItem::insert([
            [
                'combo_id'=> '1',
                'inventory_id'=> '1',
                'quantity'=> '10',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
