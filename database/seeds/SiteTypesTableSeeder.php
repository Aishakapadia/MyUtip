<?php

use Illuminate\Database\Seeder;

class SiteTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\SiteType::class)->create([
            'title'       => 'FACTORY',
            'slug'        => str_slug('FACTORY'),
            'description' => 'FACTORY'
        ]);

        factory(App\SiteType::class)->create([
            'title'       => 'PORT',
            'slug'        => str_slug('PORT'),
            'description' => 'PORT'
        ]);

        factory(App\SiteType::class)->create([
            'title'       => 'WAREHOUSE',
            'slug'        => str_slug('WAREHOUSE'),
            'description' => 'WAREHOUSE'
        ]);

        factory(App\SiteType::class)->create([
            'title'       => 'PM SUPPLIER',
            'slug'        => str_slug('PM SUPPLIER'),
            'description' => 'PM SUPPLIER'
        ]);

        factory(App\SiteType::class)->create([
            'title'       => 'RM SUPPLIER',
            'slug'        => str_slug('RM SUPPLIER'),
            'description' => 'RM SUPPLIER'
        ]);
    }
}
