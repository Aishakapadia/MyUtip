<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\VehicleType::class, 3)->create();

        factory(App\Status::class)->create([
            'role_id'     => \Config::get('constants.ROLE_ID_SUPPLIER'),
            'icon'        => 'icon-envelope-letter',
            'title'       => 'Open',
            'slug'        => str_slug('Open By Supplier'),
            'visible'     => 'Request Pending for Admin Approval',
            'description' => 'Supplier can initiate/open the ticket.',
        ]);

        factory(App\Status::class)->create([
            'role_id'     => \Config::get('constants.ROLE_ID_ADMIN'),
            'icon'        => 'icon-close',
            'title'       => 'Cancel',
            'slug'        => str_slug('Cancel by admin'),
            'visible'     => 'Request Cancelled By Admin',
            'description' => 'If cancelled by admin ticket will be closed and removed from the system.',
        ]);

        factory(App\Status::class)->create([
            'role_id'     => \Config::get('constants.ROLE_ID_ADMIN'),
            'icon'        => 'icon-check',
            'title'       => 'Approve',
            'slug'        => str_slug('Approve By Admin'),
            'visible'     => 'Transporter Submission Pending',
            'description' => 'Admin approved the request.',
        ]);

        factory(App\Status::class)->create([
            'role_id'     => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'icon'        => 'icon-check',
            'title'       => 'Accept',
            'slug'        => str_slug('Accept By Transporter'),
            'visible'     => 'In Progress - Transporters',
            'description' => 'Transporter will provide vehicle number, driver contact number and eta.',
        ]);

        factory(App\Status::class)->create([
            'role_id'     => \Config::get('constants.ROLE_ID_ADMIN'),
            'icon'        => 'icon-check',
            'title'       => 'Confirm Transporter',
            'slug'        => str_slug('Confirm Transporter By Admin'),
            'visible'     => 'Transporter Selected',
            'description' => 'When admin confirm a transporter\'s ticket, all other tickets/requests will be removed from other transporters.',
        ]);

        factory(App\Status::class)->create([
            'role_id'     => \Config::get('constants.ROLE_ID_SUPPLIER'),
            'icon'        => 'icon-action-redo',
            'title'       => 'Vehicle Arrived',
            'slug'        => str_slug('Vehicle Arrived By Supplier'),
            'visible'     => 'Vehicle arrived at start point',
            'description' => 'Supplier will update while vehicle has arrived at start point',
        ]);

        factory(App\Status::class)->create([
            'role_id'     => \Config::get('constants.ROLE_ID_SUPPLIER'),
            'icon'        => 'icon-close',
            'title'       => 'Cancel',
            'slug'        => str_slug('Cancelled by Supplier'),
            'visible'     => 'Vehicle Cancelled By Supplier',
            'description' => 'If cancelled by supplier, the ticket will go back to chosen transporter to send back another vehicle with details.',
        ]);

        factory(App\Status::class)->create([
            'role_id'     => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'icon'        => 'icon-check',
            'title'       => 'Update',
            'slug'        => str_slug('Updated by Transporter'),
            'visible'     => 'Transporter Selected',
            'description' => 'Transporter will have to provide again correct vehicle with vehicle number and driver contact number.',
        ]);

        factory(App\Status::class)->create([
            'role_id'     => \Config::get('constants.ROLE_ID_SUPPLIER'),
            'icon'        => 'icon-check',
            'title'       => 'Approve',
            'slug'        => str_slug('Vehicle Approved by Supplier'),
            'visible'     => 'Vehicle Approved by Supplier',
            'description' => 'Approve by Supplier',
        ]);

        factory(App\Status::class)->create([
            'role_id'     => \Config::get('constants.ROLE_ID_SUPPLIER'),
            'icon'        => 'icon-check',
            'title'       => 'Delivery Challan Updated',
            'slug'        => str_slug('Delivery Challan Updated By Supplier'),
            'visible'     => 'Delivery Challan Updated',
            'description' => 'Supplier will share Delivery Challan',
        ]);

        factory(App\Status::class)->create([
            'role_id'     => \Config::get('constants.ROLE_ID_SUPPLIER'),
            'icon'        => 'icon-anchor',
            'title'       => 'Vehicle Loaded',
            'slug'        => str_slug('Vehicle Loaded By Supplier'),
            'visible'     => 'Vehicle Loaded',
            'description' => 'Vehicle loaded updated by supplier.',
        ]);

        factory(App\Status::class)->create([
            'role_id'     => \Config::get('constants.ROLE_ID_SITE_TEAM'),
            'icon'        => 'icon-pointer',
            'title'       => 'Vehicle reached at destination',
            'slug'        => str_slug('Vehicle reached at destination by Site Team'),
            'visible'     => 'Vehicle reached at destination',
            'description' => 'Site Team will update arrived',
        ]);

        factory(App\Status::class)->create([
            'role_id'     => \Config::get('constants.ROLE_ID_SITE_TEAM'),
            'icon'        => 'icon-anchor',
            'title'       => 'Vehicle Offloaded',
            'slug'        => str_slug('Vehicle Offloaded by Site Team'),
            'visible'     => 'Vehicle Offloaded',
            'description' => 'Now the ticket will be closed.',
        ]);

    }
}
