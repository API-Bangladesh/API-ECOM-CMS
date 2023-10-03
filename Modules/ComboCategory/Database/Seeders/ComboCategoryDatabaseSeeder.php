<?php

namespace Modules\ComboCategory\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\ComboCategory\Entities\ComboCategory;

class ComboCategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        ComboCategory::insert([
            [
                'id'=> '1',
                'name'=> 'Chips',
                'image'=> 'chips.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'=> '2',
                'name'=> 'Biscuits',
                'image'=> 'biscuits.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // $this->call("OthersTableSeeder");
    }
}
