<?php

use Illuminate\Database\Seeder;

class VehicleTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\VehicleType::class, 3)->create();

        factory(App\VehicleType::class)->create([
            'title'       => '40 Ft Container (30-40)',
//            'slug'        => str_slug('40 Ft Container (30-40)'),
            'description' => '40 Ft Container (30-40)',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => '40 Ft Container (20-30)',
//            'slug'        => str_slug('40 Ft Container (20-30)'),
            'description' => '40 Ft Container (20-30)',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => '40 Ft Container (10-20)',
//            'slug'        => str_slug('40 Ft Container (10-20)'),
            'description' => '40 Ft Container (10-20)',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => '40 Ft Container (1-10 )',
//            'slug'        => str_slug('40 Ft Container (1-10 )'),
            'description' => '40 Ft Container (1-10 )',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => '20 Ft Container (20-27)',
//            'slug'        => str_slug('20 Ft Container (20-27)'),
            'description' => '20 Ft Container (20-27)',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => '20 Ft Container (15-20)',
//            'slug'        => str_slug('20 Ft Container (15-20)'),
            'description' => '20 Ft Container (15-20)',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => '20 Ft Container (10-15)',
//            'slug'        => str_slug('20 Ft Container (10-15)'),
            'description' => '20 Ft Container (10-15)',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => '20 Ft Container (6-10)',
//            'slug'        => str_slug('20 Ft Container (6-10)'),
            'description' => '20 Ft Container (6-10)',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => '20 Ft Container (1-5)',
//            'slug'        => str_slug('20 Ft Container (1-5)'),
            'description' => '20 Ft Container (1-5)',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => '20 Ft Shuttle Vehicle',
//            'slug'        => str_slug('20 Ft Shuttle Vehicle'),
            'description' => '20 Ft Shuttle Vehicle',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => '16 ft Mazda',
//            'slug'        => str_slug('16 ft Mazda'),
            'description' => '16 ft Mazda',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => 'Shehzore - 12 Ft Vehicle',
//            'slug'        => str_slug('Shehzore - 12 Ft Vehicle'),
            'description' => 'Shehzore - 12 Ft Vehicle',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => 'Suzuki Pick-Up',
//            'slug'        => str_slug('Suzuki Pick-Up'),
            'description' => 'Suzuki Pick-Up',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => 'ISO container 20 ft',
//            'slug'        => str_slug('ISO container 20 ft'),
            'description' => 'ISO container 20 ft',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => 'ISO container 40 ft',
//            'slug'        => str_slug('ISO container 40 ft'),
            'description' => 'ISO container 40 ft',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => 'ISO tanker',
//            'slug'        => str_slug('ISO tanker'),
            'description' => 'ISO tanker',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => 'Reefer container 20 ft',
//            'slug'        => str_slug('Reefer container 20 ft'),
            'description' => 'Reefer container 20 ft',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\VehicleType::class)->create([
            'title'       => 'Reefer container 40 ft',
//            'slug'        => str_slug('Reefer container 40 ft'),
            'description' => 'Reefer container 40 ft',
            'created_at'  => date('Y-m-d H:i:s')
        ]);
    }
}
