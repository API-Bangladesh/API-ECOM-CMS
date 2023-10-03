<?php

namespace Modules\Combo\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Combo\Entities\ComboImage;

class ComboImageDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        ComboImage::insert([
            [
                'combo_id'=> '1',
                'image'=> '6.jpg',
                'status'=> '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'combo_id'=> '1',
                'image'=> '7.jpg',
                'status'=> '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
