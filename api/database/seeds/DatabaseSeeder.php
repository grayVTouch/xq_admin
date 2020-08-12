<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminPermissionSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(NavSeeder::class);
        $this->call(PositionSeeder::class);
    }
}
