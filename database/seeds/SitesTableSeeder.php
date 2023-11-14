<?php

use Illuminate\Database\Seeder;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\Site::class, 3)->create();

        /*
        $dataFromSites = [
            ['Afeef Zehra - Port Qasim', 'RM/PM', 'Supplier'],
            ['Afeef Packages - Korangi', 'PM', 'Supplier'],
            ['Amin Industry', 'RM/PM', 'Supplier'],
            ['Burhani Industries (Pvt) Ltd', 'RM/PM', 'Supplier'],
            ['Century Lahore', 'RM/PM', 'Supplier'],
            ['Decent Packages Pvt Ltd', 'RM/PM', 'Supplier'],
            ['Elegent Industries', 'RM/PM', 'Supplier'],
            ['Ever Shine Pvt Ltd', 'RM/PM', 'Supplier'],
            ['karim container (Pvt) Ltd', 'RM/PM', 'Supplier'],
            ['M&F Pvt Ltd - Shafiq More', 'PM', 'Supplier'],
            ['M&F Pvt Ltd - Gulshan-e-Maymar', 'PM', 'Supplier'],
            ['IFFCO Pakistan', 'RM/PM', 'Supplier'],
            ['Newaga Lahore', 'RM/PM', 'Supplier'],
            ['Bullehshah karachi', 'PM', 'Supplier'],
            ['Bullehshah Kasur', 'PM', 'Supplier'],
            ['Packages Lahore', 'PM', 'Supplier'],
            ['Roshan Packages - Flexible', 'PM', 'Supplier'],
            ['Roshan Packages - Corrugation', 'PM', 'Supplier'],
            ['SPEL  Lahore', 'RM/PM', 'Supplier'],
            ['SPEL RYK', 'RM/PM', 'Supplier'],
            ['Sunrise Pvt Ltd - Karachi', 'PM', 'Supplier'],
            ['Thermoplas Pvt Ltd', 'RM/PM', 'Supplier'],
            ['Agility WH', 'RM/PM', 'Supplier'],
            ['MEO (Pvt) Ltd (Port Qasim)', 'RM/PM', 'Supplier'],
        ];

        if ($dataFromSites) {
            foreach ($dataFromSites as $datum) {
                $site_type = \App\SiteType::firstOrCreate(['title' => $datum[2], 'slug' => $datum[2]]);

                $site = factory(App\Site::class)->create([
                    'site_type_id'  => $site_type->id,
                    'title'         => $datum[0],
                    'description'   => $datum[0],
                    'material_type' => $datum[1],
                    'from_or_to'    => 0,
                ]);
            }
        }
        */


        $data = [
            [
                'title'     => 'Afeef Zehra - Port Qasim', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Afeef Packages - Korangi', 'material_type' => 'PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Amin Industry', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Burhani Industries (Pvt) Ltd', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Century Lahore', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Decent Packages Pvt Ltd', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Elegent Industries', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Ever Shine Pvt Ltd', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'karim container (Pvt) Ltd', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'M&F Pvt Ltd - Shafiq More', 'material_type' => 'PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'M&F Pvt Ltd - Gulshan-e-Maymar', 'material_type' => 'PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'IFFCO Pakistan', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Newaga Lahore', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Bullehshah karachi', 'material_type' => 'PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Bullehshah Kasur', 'material_type' => 'PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Packages Lahore', 'material_type' => 'PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Roshan Packages - Flexible', 'material_type' => 'PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Roshan Packages - Corrugation', 'material_type' => 'PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'SPEL  Lahore', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'SPEL RYK', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Sunrise Pvt Ltd - Karachi', 'material_type' => 'PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Thermoplas Pvt Ltd', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'Agility WH', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            [
                'title'     => 'MEO (Pvt) Ltd (Port Qasim)', 'material_type' => 'RM/PM', 'site_type' => 'Supplier',
                'users'     => [],
                'materials' => [],
            ],
            ['title'     => 'OFD RYK ', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['Tasaddaq-Hussain.RBF@unilever.com', '03027642191, 03049532734'], ['Javed.akhtarofd@gmail.com', '03027642191, 03049532734'],],
             'materials' => []
            ],
            ['title'     => 'Glacier WH', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['aminventory@glaciers.com.pk', '03014874072, 03316462619'], ['secondary@glaciers.com.pk', '03014874072, 03316462619'],],
             'materials' => []
            ],
            ['title'     => 'ICF ', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['Ghulam.Mustafa@unilever.com', '0332-4991732'],],
             'materials' => []
            ],
            ['title'     => 'UPLFL', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['Muhammad.Akmal@unilever.com', '0301-7911133,  0301-720664'], ['Sarwar.Yaqoob@unilever.com', '0301-7911133,  0301-720664'],],
             'materials' => []
            ],
            ['title'     => 'Raazik WH', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['tasneem.ashraf@raaziq.com.pk', '03344885101, 03314999251'], ['asif.hussain@raaziq.com.pk', '03344885101, 03314999251'],],
             'materials' => []
            ],
            ['title'     => 'Greenland', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['Greenlandcoldchain@gmail.com', '03003602827, 03024463101'],],
             'materials' => []
            ],
            ['title'     => 'HBM', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['Abid.Sabir@unilever.com', '03028282748'],],
             'materials' => []
            ],
            ['title'     => 'RMPC Jaranwala', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['farooq@rafhanmaize.com', '03447699499, 03009653909'], ['ahsan@rafhanmaize.com', '03447699499, 03009653909'],],
             'materials' => []
            ],
            ['title'     => 'Artic', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['whm-khi@sanalogistics.com', '03452860995, 03208233007'], ['opssl1.r@sanalogistics.com', '03452860995, 03208233007'],],
             'materials' => []
            ],
            ['title'     => 'Connect Logistics', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['operations@connectlogistics.pk', '03118153125, 03142552216'], ['dispatch@connectlogistics.pk', '03118153125, 03142552216'],],
             'materials' => []
            ],
            ['title'     => 'External WH -RYK', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['spcexternalwh@hotmail.com', '03009636277'],],
             'materials' => []
            ],
            ['title'     => 'Fecto W/h ', 'material_type' => 'RM/PM', 'site_type' => 'WH',
                //'users'     => [['fectowarehouse@yahoo.com', '03008445789, 03434126050'], ['spclahore@gmail.com', '03008445789, 03434126050'],],
             'users'     => [['fectowarehouse@yahoo.com', '03008445789, 03434126050']],
             'materials' => []
            ],
            ['title'     => 'FCL', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['wahid.durvesh@fcl.com.pk', '03028237188, 03452368117, 03453178511'], ['Naveed.ahsan@fcl.com.pk', '03028237188, 03452368117, 03453178511'],],
             'materials' => []
            ],
            ['title'     => 'KTF', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['Mahmood.Khaskheli@unilever.com', '03212625015, 03472458516, 03453309003'], ['ktf.bof@emirateslogistics.net', '03212625015, 03472458516, 03453309003'],],
             'materials' => []
            ],
            ['title'     => 'AGA Pack', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['Zohaib.ali@unilever.com', '0300-3601843'],],
             'materials' => []
            ],
            ['title'     => 'RM/PM LDC', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['Rm.ldc@emirateslogistics.net', '03349926811'],],
             'materials' => []
            ],
            ['title'     => 'WWG', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['anjum_za@yahoo.com', '03452272649, 03002171011, 03218744911'], ['syedmansoor10@yahoo.com', '03452272649, 03002171011, 03218744911'],],
             'materials' => []
            ],
            ['title'     => 'ZTL', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['ofdupl.lhr@emirateslogistics.net', '03004260574'],],
             'materials' => []
            ],
            ['title'     => 'PCP', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['rizwanfeda@pakistancosmetics.com', '03000445093'],],
             'materials' => []
            ],
            ['title'     => 'RYK Factory', 'material_type' => 'RM/PM', 'site_type' => 'WH',
//             'users'     => [['Qasim.Naveed@unilever.com', '03006700608, 03028265019, 03007822083'], ['Tariq.Wahab@unilever.com', '03006700608, 03028265019, 03007822083'],],
             'users'     => [['Tariq.Wahab@unilever.com', '03006700608, 03028265019, 03007822083'],],
             'materials' => []
            ],
            ['title'     => 'Foods Factory', 'material_type' => 'RM/PM', 'site_type' => 'WH',
                //'users'     => [['Shahid.Qureshi@unilever.com', '03042225459, 03017911133, 03017206641'], ['Muhammad.Akmal@unilever.com', '03042225459, 03017911133, 03017206641'],],
             'users'     => [['Shahid.Qureshi@unilever.com', '03042225459, 03017911133, 03017206641']],
             'materials' => []
            ],
            ['title'     => 'ICF 2', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['Adil.Sultan@unilever.com', '03018226943, 03013260721, 03006704805'], ['M-Zafar.Iqbal@unilever.com', '03018226943, 03013260721, 03006704805'],],
             'materials' => []
            ],
            ['title'     => 'UD 2', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['store-unit2@uniteddetergents.com', '0300-0721050'],],
             'materials' => []
            ],
            ['title'     => 'UDL1', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['zeeshan.ali@uniteddetergents.com', '0300-0721030, 0304-4477434'], ['storeud1@gmail.com', '0300-0721030, 0304-4477434'],],
             'materials' => []
            ],
            ['title'     => 'MUL WH', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['Imran.Sajid2@unilever.com', '0307-0015870'],],
             'materials' => []
            ],
            ['title'     => 'Fecto W/h ', 'material_type' => 'RM/PM', 'site_type' => 'WH',
                //'users'     => [['spclahore@gmail.com', ''],],
             'users'     => [],
             'materials' => []
            ],
            ['title'     => 'Presto WH', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['Ghulam.Dastagir@unilever.com', '0300-8393769'],],
             'materials' => []
            ],
            ['title'     => 'Nimir', 'material_type' => 'RM/PM', 'site_type' => 'WH',
             'users'     => [['Qasim.Naveed@unilever.com', '0300-6700608'],],
             'materials' => []
            ],
            ['title'     => 'Khanewal Tea Factory', 'material_type' => 'RM/PM', 'site_type' => 'WH',
//             'users'     => [['Ghulam.Dastagir@unilever.com', '03008393769, 03018273085'], ['Azhar.Ghaffar@unilever.com', '03008393769, 03018273085'],],
             'users'     => [['Azhar.Ghaffar@unilever.com', '03008393769, 03018273085'],],
             'materials' => []
            ],
        ];

        if ($data) {

            foreach ($data as $datum) {
                $site_type = \App\SiteType::firstOrCreate(['title' => $datum['site_type'], 'slug' => $datum['site_type']]);

                $site = factory(App\Site::class)->create([
                    'site_type_id'  => $site_type->id,
                    'title'         => $datum['title'],
                    'description'   => $datum['title'],
                    'material_type' => $datum['material_type'],
                    //'from_or_to'    => 1,
                ]);

                if ($datum['users']) {
                    foreach ($datum['users'] as $user) {

                        $email = $user[0];
                        $email = strtolower($email);
                        $name = explode('@', $email);

                        $user = factory(App\User::class)->create([
                            'name'     => $name[0],
                            'email'    => $email,
                            'password' => bcrypt('Unilever123'),
                            'role_id'  => \Config::get('constants.ROLE_ID_SITE_TEAM'),
                            'mobile'   => $user[1]
                        ]);

                        $user->sites()->attach([$site->id]);
                    }
                }
            }

//            foreach ($data as $row) {
//
//                $site_type = \App\SiteType::firstOrCreate(['title' => $row[2], 'slug' => $row[2]]);
//
//                $site = factory(App\Site::class)->create([
//                    'site_type_id'  => $site_type->id,
//                    'title'         => $row[0],
////                    'slug'          => str_slug($row[0]),
//                    'description'   => $row[0],
//                    'material_type' => $row[1],
//                    //'site_type'     => $row[2],
//                ]);
//            }
        }
    }
}
