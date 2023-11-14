<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Super Admin
         */
        factory(App\User::class)->create([
            'name'     => 'Super Admin',
            'email'    => 'zarpio@gmail.com',
            'password' => bcrypt('Unilever762'),
            'role_id'  => \Config::get('constants.ROLE_ID_SUPER'),
            'mobile'   => '03212441860'
        ]);

        //region Admin Users
        factory(App\User::class)->create([
            'name'     => 'Rida Shazli',
            'email'    => 'rida.shazli@unilever.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_ADMIN'),
            'mobile'   => '03312273244'
        ]);

        factory(App\User::class)->create([
            'name'     => 'mehdi zulfiqar',
            'email'    => 'mehdi.zulfiqar@unilever.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_ADMIN'),
            'mobile'   => '03002600719'
        ]);
        //endregion

        //region Viewer Users
        factory(App\User::class)->create([
            'name'     => 'Viewer',
            'email'    => 'viewer@unilever.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_VIEWER'),
            'mobile'   => ''
        ]);
        //endregion

        //region Suppliers Users
        $supplier_users = [
            'hameed.gul@afeefgroup.com.pk',
            'faizan.iqbal@afeefgroup.com.pk',
            'Amin.industry@gmail.com',
            'supplychain@burhani.com.pk',
            'kashif-javed@centurypaper.com.pk',
            'packages.decent@gmail.com',
            'elegantindustries@hotmail.com',
            'farhan12@hotmail.com',
            'kclpd@hotmail.com',
            'Dispatch@mfcorp.biz',
            'sales2@mfcorp.biz',
            'nlaghari@iffco.com',
            'Plant.Plastics@gmail.com',
            'mansoor.malik@bullehshah.com.pk',
            'noor.zaman@bullehshah.com.pk',
            'atif.akram@packages.com.pk',
            'dispatch.flexible@roshanpackages.com.pk',
            'dispatch@roshanpackages.com.pk',
            'marketing5@spelgroup.com',
            'productionryk@spelgroup.com',
            'atif.raza@sunriseplastic.com.pk',
            'irtaza@thermoplas.com',
            'Mansoor.Hassan@unilever.com',
            'umerfarooq@mapak.com',
            'qc@burhani.com.pk',
            'nabeel-akram@centurypaper.com.pk',
            'HRehman@iffco.com',
            'raza.minhas@bullehshah.com.pk',
            'shabbir.ahmad@packages.com.pk',
            'sumairmoawiya@outlook.com',
        ];

        if ($supplier_users) {
            foreach ($supplier_users as $user) {
                $fullName = explode('@', $user);

                factory(App\User::class)->create([
                    'name'     => $fullName[0],
                    'email'    => strtolower($user),
                    'password' => bcrypt('Unilever123'),
                    'role_id'  => \Config::get('constants.ROLE_ID_SUPPLIER'),
                ]);
            }
        }
        //endregion

        //region Transporter Users
        $user = factory(App\User::class)->create([
            'name'     => 'Transporter 2 (Connect Logistics)',
            'email'    => 'transport2@connectlogistics.pk',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03218205537'
        ]);
        $user->transporters()->attach([\Config::get('constants.CONNECT_LOGISTICS')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Coordinator (Connect Logistics)',
            'email'    => 'coordinator@connectlogistics.pk',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03218201532'
        ]);
        $user->transporters()->attach([\Config::get('constants.CONNECT_LOGISTICS')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Munis Ayaz (Open Port)',
            'email'    => 'munis.ayaz@openport.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03000569742'
        ]);
        $user->transporters()->attach([\Config::get('constants.OPEN_PORT')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Farhan Ali (Open Port)',
            'email'    => 'farhan.ali@openport.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '0342-2690909'
        ]);
        $user->transporters()->attach([\Config::get('constants.OPEN_PORT')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Jimmy Goods',
            'email'    => 'jimmygoodstransport@hotmail.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03002006745, 03008207854'
        ]);
        $user->transporters()->attach([\Config::get('constants.JIMMY_GOODS')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Alhaider Transport (Al-Hayder)',
            'email'    => 'alhaidertransportagency@yahoo.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03018451012'
        ]);
        $user->transporters()->attach([\Config::get('constants.AL_HAYDER')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Aaqil Hussain (Al-Hayder)',
            'email'    => 'aaqilhussain13@yahoo.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03012333528'
        ]);
        $user->transporters()->attach([\Config::get('constants.AL_HAYDER')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Al-Hyder 46 (Al-Hayder)',
            'email'    => 'alhyder46@gmail.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03458214486'
        ]);
        $user->transporters()->attach([\Config::get('constants.AL_HAYDER')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Chaudhary Goods',
            'email'    => 'thechaudharygoods@yahoo.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03218090786, 03334217186'
        ]);
        $user->transporters()->attach([\Config::get('constants.CHAUDHARY_GOODS')]);

        $user = factory(App\User::class)->create([
            'name'     => 'SPC',
            'email'    => 'spclahore@gmail.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => ''
        ]);
        $user->transporters()->attach([\Config::get('constants.SPC')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Waleed Goods',
            'email'    => 'waleedgts@gmail.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => ''
        ]);
        $user->transporters()->attach([\Config::get('constants.WALEED_GOODS')]);

        $user = factory(App\User::class)->create([
            'name'     => 'KCI (NLC)',
            'email'    => 'kci.mktg@NLC.com.pk',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03333240645, 03225107865, 03343846608'
        ]);
        $user->transporters()->attach([\Config::get('constants.NLC')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Karim Dossani (NCL)',
            'email'    => 'karim.Dossani@nlc.com.pk',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03333240645, 03225107865, 03343846608'
        ]);
        $user->transporters()->attach([\Config::get('constants.NLC')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Khawaja Azam (TSD)',
            'email'    => 'khawaja.azam@pkbsl.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03088882327'
        ]);
        $user->transporters()->attach([\Config::get('constants.TSD')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Abdullah Enterprises',
            'email'    => 'abdullahenterprises85@gmail.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03226391196'
        ]);
        $user->transporters()->attach([\Config::get('constants.ABDULLAH_ENTERPRISES')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Amjali (Agility)',
            'email'    => 'amjali@agility.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03468212376, 03018278948'
        ]);
        $user->transporters()->attach([\Config::get('constants.AGILITY')]);

        $user = factory(App\User::class)->create([
            'name'     => 'Hawan (Agility)',
            'email'    => 'hawan@agility.com',
            'password' => bcrypt('Unilever123'),
            'role_id'  => \Config::get('constants.ROLE_ID_TRANSPORTER'),
            'mobile'   => '03468212376, 03018278948'
        ]);
        $user->transporters()->attach([\Config::get('constants.AGILITY')]);
        //endregion

        //region Site Users
        //endregion

    }
}
