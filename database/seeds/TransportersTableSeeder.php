<?php

use Illuminate\Database\Seeder;

class TransportersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\Transporter::class, 3)->create();

        factory(App\Transporter::class)->create([
            'title'       => 'Connect Logistics',
//            'slug'        => str_slug('Connect Logistics'),
            'description' => 'Connect Logistics',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Transporter::class)->create([
            'title'       => 'Open Port',
//            'slug'        => str_slug('Open Port'),
            'description' => 'Open Port',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Transporter::class)->create([
            'title'       => 'Jimmy Goods',
//            'slug'        => str_slug('Jimmy Goods'),
            'description' => 'Jimmy Goods',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Transporter::class)->create([
            'title'       => 'Al-Hayder',
//            'slug'        => str_slug('Al-Hayder'),
            'description' => 'Al-Hayder',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Transporter::class)->create([
            'title'       => 'Chaudhary Goods',
//            'slug'        => str_slug('Chaudhary Goods'),
            'description' => 'Chaudhary Goods',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Transporter::class)->create([
            'title'       => 'SPC',
//            'slug'        => str_slug('SPC'),
            'description' => 'SPC',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Transporter::class)->create([
            'title'       => 'Waleed Goods',
//            'slug'        => str_slug('Waleed Goods'),
            'description' => 'Waleed Goods',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Transporter::class)->create([
            'title'       => 'NLC',
//            'slug'        => str_slug('NLC'),
            'description' => 'NLC',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Transporter::class)->create([
            'title'       => 'TSD',
//            'slug'        => str_slug('TSD'),
            'description' => 'TSD',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Transporter::class)->create([
            'title'       => 'Abdullah Enterprises',
//            'slug'        => str_slug('Abdullah Enterprises'),
            'description' => 'Abdullah Enterprises',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        factory(App\Transporter::class)->create([
            'title'       => 'Agility',
//            'slug'        => str_slug('Agility'),
            'description' => 'Agility',
            'created_at'  => date('Y-m-d H:i:s')
        ]);
    }
}
