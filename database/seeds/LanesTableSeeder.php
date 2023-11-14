<?php

use Illuminate\Database\Seeder;

class LanesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lane = factory(App\Lane::class)->create([
            'title'        => 'Afeef Packages - Korangi - ICF',
            'description'  => 'Afeef Packages - Korangi - ICF',
            'site_id_from' => 2,
            'site_id_to'   => 27,
        ]);
        $lane->transporters()->attach([1, 2, 3, 4]);

        $lane = factory(App\Lane::class)->create([
            'title'        => 'Afeef Zehra - Port Qasim - ICF',
            'description'  => 'Afeef Zehra - Port Qasim - ICF',
            'site_id_from' => 1,
            'site_id_to'   => 27,
        ]);
        $lane->transporters()->attach([1, 2, 3, 4]);
    }
}
