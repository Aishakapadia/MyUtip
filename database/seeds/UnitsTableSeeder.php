<?php

use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\Unit::class, 3)->create();

        factory(App\Unit::class)->create([
            'title'       => 'Bags',
//            'slug'        => str_slug('Bags'),
            'description' => 'Bags',
            'active'      => 1,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Unit::class)->create([
            'title'       => 'Cartons',
//            'slug'        => str_slug('Cartons'),
            'description' => 'Cartons',
            'active'      => 1,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Unit::class)->create([
            'title'       => 'Cans',
//            'slug'        => str_slug('Cans'),
            'description' => 'Cans',
            'active'      => 1,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Unit::class)->create([
            'title'       => 'Drums',
//            'slug'        => str_slug('Drums'),
            'description' => 'Drums',
            'active'      => 1,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Unit::class)->create([
            'title'       => 'Buckets',
//            'slug'        => str_slug('Buckets'),
            'description' => 'Buckets',
            'active'      => 1,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Unit::class)->create([
            'title'       => 'Cases',
//            'slug'        => str_slug('Cases'),
            'description' => 'Cases',
            'active'      => 1,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Unit::class)->create([
            'title'       => 'Flexi Bags',
//            'slug'        => str_slug('Flexi Bags'),
            'description' => 'Flexi Bags',
            'active'      => 1,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Unit::class)->create([
            'title'       => 'Jumbo Bags',
//            'slug'        => str_slug('Jumbo Bags'),
            'description' => 'Jumbo Bags',
            'active'      => 1,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Unit::class)->create([
            'title'       => 'PC',
//            'slug'        => str_slug('PC'),
            'description' => 'PC',
            'active'      => 1,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Unit::class)->create([
            'title'       => 'IBC Tanks',
//            'slug'        => str_slug('IBC Tanks'),
            'description' => 'IBC Tanks',
            'active'      => 1,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Unit::class)->create([
            'title'       => 'ROL',
//            'slug'        => str_slug('ROL'),
            'description' => 'ROL',
            'active'      => 1,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Unit::class)->create([
            'title'       => 'T',
//            'slug'        => str_slug('T'),
            'description' => 'T',
            'active'      => 1,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

    }
}
