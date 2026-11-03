<?php

namespace Database\Seeders;

class DatabaseSeeder
{
    public function run(): void
    {
        (new RoleAndPermissionSeeder())->run();
        (new DestinationAndSupplierSeeder())->run();
        (new ProductSeeder())->run();
        (new DemoBookingSeeder())->run();
    }
}
