<?php

namespace Modules\Review\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Review\Entities\Review;

class ReviewDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Review::insert([
            [
                'ratting_number' => '4',
                'comments' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit nunc magnis elementum, lacus a eget nisi at lacinia augue est nullam.',
                'customer_id' => '1',
                'inventory_id' => '1',
                'combo_id' => Null,
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ratting_number' => '3',
                'comments' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit nunc magnis elementum, lacus a eget nisi at lacinia augue est nullam.',
                'customer_id' => '1',
                'inventory_id' => Null,
                'combo_id' => '1',
                'status' => '2',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // $this->call("OthersTableSeeder");
    }
}
