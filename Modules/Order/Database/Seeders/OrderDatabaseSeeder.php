<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderItem;

class OrderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Order::insert([
            [
                'id' => 1,
                'order_date' => now(),
                'customer_id' => '1',
                'shipping_address' => 'House 04, Flat, 7A Road 23/A, Dhaka 1213',
                'billing_address' => 'House 04, Flat, 7A Road 23/A, Dhaka 1213',
                'sub_total' => '120',
                'discount' => '10',
                'shipping_charge' => '5',
                'tax' => '0',
                'grand_total' => '115',
                'payment_method_id' => '',
                'payment_details' => json_encode([]),
                'payment_status_id' => Order::PAYMENT_STATUS_PAID,
                'order_status_id' => Order::ORDER_STATUS_DELIVERED,
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'id' => 2,
                'order_date' => now(),
                'customer_id' => '1',
                'shipping_address' => 'House 04, Flat, 7A Road 23/A, Dhaka 1213',
                'billing_address' => 'House 04, Flat, 7A Road 23/A, Dhaka 1213',
                'total' => '120',
                'discount' => '10',
                'shipping_charge' => '5',
                'tax' => '0',
                'grand_total' => '115',
                'payment_method_id' => '',
                'payment_details' => json_encode([]),
                'payment_status_id' => Order::PAYMENT_STATUS_PAID,
                'order_status_id' => Order::ORDER_STATUS_DELIVERED,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);


        $this->call(OrderItemsDatabaseSeeder::class);
    }
}
