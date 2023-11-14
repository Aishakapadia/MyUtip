<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        //$this->call(PagesTableSeeder::class);

        $this->call(VehicleTypesTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
        $this->call(TransporterStatusesTableSeeder::class);
        //$this->call(SiteTypesTableSeeder::class);
        $this->call(SitesTableSeeder::class);
        $this->call(TransportersTableSeeder::class);
        $this->call(MaterialsTableSeeder::class);
        $this->call(UnitsTableSeeder::class);
        $this->call(LanesTableSeeder::class);
    }
}
