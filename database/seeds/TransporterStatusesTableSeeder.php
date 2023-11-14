<?php

use Illuminate\Database\Seeder;

class TransporterStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\VehicleType::class, 3)->create();

        factory(App\TransporterStatus::class)->create([
            'title'       => 'WIP',
            'slug'        => str_slug('WIP'),
            'description' => 'WIP',
        ]);

        factory(App\TransporterStatus::class)->create([
            'title'       => 'Bid Submitted',
            'slug'        => str_slug('Bid Submitted'),
            'description' => 'Bid Submitted By Transporter',
        ]);

        factory(App\TransporterStatus::class)->create([
            'title'       => 'On Hold',
            'slug'        => str_slug('On Hold'),
            'description' => 'On Hold',
        ]);

        factory(App\TransporterStatus::class)->create([
            'title'       => 'Accepted By Admin',
            'slug'        => str_slug('Accepted By Admin'),
            'description' => 'Accepted By Admin',
            'color_code'  => '#fdffa8',
        ]);

        factory(App\TransporterStatus::class)->create([
            'title'       => 'Accepted By Supplier',
            'slug'        => str_slug('Accepted By Supplier'),
            'description' => 'Accepted By Supplier',
            'color_code'  => '#97ff97',
        ]);

        factory(App\TransporterStatus::class)->create([
            'title'       => 'Accepted By Transporter',
            'slug'        => str_slug('Accepted By Transporter'),
            'description' => 'Accepted By Transporter',
        ]);

        factory(App\TransporterStatus::class)->create([
            'title'       => 'Rejected By Admin',
            'slug'        => str_slug('Rejected By Admin'),
            'description' => 'Rejected By Admin',
            'color_code'  => '#ff8686',
        ]);

        factory(App\TransporterStatus::class)->create([
            'title'       => 'Rejected By Supplier',
            'slug'        => str_slug('Rejected By Supplier'),
            'description' => 'Rejected By Supplier',
        ]);

        factory(App\TransporterStatus::class)->create([
            'title'       => 'Rejected By Transporter',
            'slug'        => str_slug('Rejected By Transporter'),
            'description' => 'Rejected By Transporter',
        ]);
    }
}
