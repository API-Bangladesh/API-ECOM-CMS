<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DemoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Demo migration and seed.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Artisan::call('module:migrate-refresh Customers');
        Artisan::call('module:seed Customers');

        Artisan::call('module:migrate-refresh PaymentMethod');
        Artisan::call('module:seed PaymentMethod');

        Artisan::call('module:migrate-refresh Inventory');
        Artisan::call('module:seed Inventory');

        Artisan::call('module:migrate-refresh InventoryImage');
        Artisan::call('module:seed InventoryImage');

        Artisan::call('module:migrate-refresh Order');
        Artisan::call('module:seed Order');

        Artisan::call('module:migrate-refresh ComboCategory');
        Artisan::call('module:seed ComboCategory');

        Artisan::call('module:migrate-refresh Combo');
        Artisan::call('module:seed Combo');

        Artisan::call('module:migrate-refresh ComboImage');
        Artisan::call('module:seed ComboImage');

        Artisan::call('module:migrate-refresh Wishlist');
        Artisan::call('module:seed Wishlist');

        Artisan::call('module:migrate-refresh Address');
        Artisan::call('module:seed Address');

        Artisan::call('module:migrate-refresh Review');
        Artisan::call('module:seed Review');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "Migration & Seed Completed.";
    }
}
