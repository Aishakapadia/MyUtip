<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['Super', 'Super Admin'],
            ['Administrator', 'Administrator'],
            ['Supplier', 'Suppliers are requester.'],
            ['Transporter', 'Transporter users'],
            ['Viewer', 'Viewer users'],
            ['Site Team', 'Site team users'],
        ];

        //DB::table('roles')->truncate();

        foreach ($roles as $row) {
            $role_id = DB::table('roles')->insertGetId(
                [
                    'title'       => $row[0],
                    'slug'        => str_slug($row[0]),
                    'description' => $row[1],
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s')
                ]
            );

            /** Seed Permissions for all roles -> allow dashboard access */
            DB::table('permissions')->insert(
                [
                    'role_id'    => $role_id,
                    'module_id'  => 1, // Dashboard
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );
        }
    }
}
