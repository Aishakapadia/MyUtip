<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = array(
            array(
                'General',
                1,
                'module_titles' => array(
                    array(
                        'Dashboard',
                        'dashboard',
                        'icon-home',
                        '0',
                        'module_lists' => array()
                    )
                )
            ),
            array(
                'Manage',
                1,
                'module_titles' => array(
                    array(
                        'Tickets',
                        '',
                        'fa fa-database',
                        '0',
                        'module_lists' => array(
                            array('Manage', 'ticket/manage', 1, 1),
                            array('Ticket Listing', 'ticket/search-data', 0, 1),
                            array('Download', 'ticket/download', 0, 5),
                            array('Create', 'ticket/create', 0, 2),
                            array('Edit', 'ticket/edit', 0, 3),
                            array('Approve', 'ticket/approve', 0, 3),
                            array('Reject', 'ticket/reject', 0, 3),
                            array('Update', 'ticket/update', 0, 3),
                            array('Status', 'ticket/status', 0, 3),
                            array('Detail', 'ticket/detail', 0, 1),
                            array('Delete', 'ticket/delete', 0, 4),
                            array('Material Form', 'ticket/material-form', 0, 4),
                            array('Group Action', 'ticket/group-action', 0, 4),
                            array('Permissions', 'ticket/permissions', 0, 1),
                        )
                    ),
                    array(
                        'Roles',
                        '',
                        'fa fa-users',
                        '0',
                        'module_lists' => array(
                            array('Manage', 'role/manage', 1, 1),
                            array('Role Listing', 'role/search-data', 0, 1),
                            array('Download', 'role/download', 0, 5),
                            array('Create', 'role/create', 0, 2),
                            array('Edit', 'role/edit', 0, 3),
                            array('Update', 'role/update', 0, 3),
                            array('Detail', 'role/detail', 0, 1),
                            array('Delete', 'role/delete', 0, 4),
                            array('Group Action', 'role/group-action', 0, 4),
                            array('Permissions', 'role/permissions', 0, 1),
                        )
                    ),
                    array(
                        'Users',
                        '',
                        'icon-users',
                        '0',
                        'module_lists' => array(
                            array('Manage', 'user/manage', 1, 1), // (title, url, visible_in_sidebar, type = 0=system, 1=view, 2=add, 3=edit, 4=delete, 5=download)
                            array('User Listing', 'user/search-data', 0, 1),
                            array('Download', 'user/download', 0, 5),
                            array('Create', 'user/create', 0, 2),
                            array('Edit', 'user/edit', 0, 3),
                            array('Update', 'user/update', 0, 3),
                            array('Detail', 'user/detail', 0, 1),
                            array('Delete', 'user/delete', 0, 4),
                            array('Group Action', 'user/group-action', 0, 4),
                        )
                    ),
                    array(
                        'Systems',
                        '',
                        '',
                        '0',
                        'module_lists' => array(
//                            array('Get Profile', 'account/profile', 0, 1), // (title, url, visible_in_sidebar, type = 0=system, 1=view, 2=add, 3=edit, 4=delete, 5=download)
//                            array('Update Profile', 'account/profile', 0, 3),
//                            array('Update Avatar', 'account/avatar', 0, 3),
                            array('Update Profile Info', 'account/account-info-update', 0, 3),
                            array('Update Profile Password', 'account/account-password-update', 0, 3),
                        )
                    ),
                    /*/
                    array(
                        'Pages',
                        '',
                        'icon-docs',
                        '0',
                        'module_lists' => array(
                            array('Manage', 'page/manage', 1, 1), // (title, url, visible_in_sidebar, type = 0=system, 1=view, 2=add, 3=edit, 4=delete, 5=download)
                            array('User Listing', 'page/search-data', 0, 1),
                            array('Download', 'page/download', 0, 5),
                            array('Create', 'page/create', 0, 2),
                            array('Edit', 'page/edit', 0, 3),
                            array('Update', 'page/update', 0, 3),
                            array('Detail', 'page/detail', 0, 1),
                            array('Delete', 'page/delete', 0, 4),
                            array('Group Action', 'page/group-action', 0, 4),
                        )
                    ),
                    //*/
                    array(
                        'Vehicle Types',
                        '',
                        'icon-disc',
                        '0',
                        'module_lists' => array(
                            array('Manage', 'vehicle-type/manage', 1, 1), // (title, url, visible_in_sidebar, type = 0=system, 1=view, 2=add, 3=edit, 4=delete, 5=download)
                            array('Vehicle Type Listing', 'vehicle-type/search-data', 0, 1),
                            array('Download', 'vehicle-type/download', 0, 5),
                            array('Create', 'vehicle-type/create', 0, 2),
                            array('Edit', 'vehicle-type/edit', 0, 3),
                            array('Update', 'vehicle-type/update', 0, 3),
                            array('Detail', 'vehicle-type/detail', 0, 1),
                            array('Delete', 'vehicle-type/delete', 0, 4),
                            array('Group Action', 'vehicle-type/group-action', 0, 4),
                        )
                    ),
                    array(
                        'Transporters',
                        '',
                        'icon-anchor',
                        '0',
                        'module_lists' => array(
                            array('Manage', 'transporter/manage', 1, 1), // (title, url, visible_in_sidebar, type = 0=system, 1=view, 2=add, 3=edit, 4=delete, 5=download)
                            array('Transporter Listing', 'transporter/search-data', 0, 1),
                            array('Download', 'transporter/download', 0, 5),
                            array('Create', 'transporter/create', 0, 2),
                            array('Edit', 'transporter/edit', 0, 3),
                            array('Update', 'transporter/update', 0, 3),
                            array('Detail', 'transporter/detail', 0, 1),
                            array('Delete', 'transporter/delete', 0, 4),
                            array('Group Action', 'transporter/group-action', 0, 4),
                        )
                    ),
                    array(
                        'Sites',
                        '',
                        'icon-pointer',
                        '0',
                        'module_lists' => array(
                            array('Manage', 'site/manage', 1, 1), // (title, url, visible_in_sidebar, type = 0=system, 1=view, 2=add, 3=edit, 4=delete, 5=download)
                            array('User Listing', 'site/search-data', 0, 1),
                            array('Download', 'site/download', 0, 5),
                            array('Create', 'site/create', 0, 2),
                            array('Edit', 'site/edit', 0, 3),
                            array('Update', 'site/update', 0, 3),
                            array('Detail', 'site/detail', 0, 1),
                            array('Delete', 'site/delete', 0, 4),
                            array('Group Action', 'site/group-action', 0, 4),
                        )
                    ),
                    array(
                        'Materials',
                        '',
                        'icon-layers',
                        '0',
                        'module_lists' => array(
                            array('Manage', 'material/manage', 1, 1), // (title, url, visible_in_sidebar, type = 0=system, 1=view, 2=add, 3=edit, 4=delete, 5=download)
                            array('Material Listing', 'material/search-data', 0, 1),
                            array('Download', 'material/download', 0, 5),
                            array('Create', 'material/create', 0, 2),
                            array('Edit', 'material/edit', 0, 3),
                            array('Update', 'material/update', 0, 3),
                            //array('Detail', 'material/detail', 0, 1),
                            array('Delete', 'material/delete', 0, 4),
                            array('Group Action', 'material/group-action', 0, 4),
                        )
                    ),
                    array(
                        'Units',
                        '',
                        'icon-social-dropbox',
                        '0',
                        'module_lists' => array(
                            array('Manage', 'unit/manage', 1, 1), // (title, url, visible_in_sidebar, type = 0=system, 1=view, 2=add, 3=edit, 4=delete, 5=download)
                            array('Unit Listing', 'unit/search-data', 0, 1),
                            array('Download', 'unit/download', 0, 5),
                            array('Create', 'unit/create', 0, 2),
                            array('Edit', 'unit/edit', 0, 3),
                            array('Update', 'unit/update', 0, 3),
                            array('Detail', 'unit/detail', 0, 1),
                            array('Delete', 'unit/delete', 0, 4),
                            array('Group Action', 'unit/group-action', 0, 4),
                        )
                    ),
                    array(
                        'Lanes',
                        '',
                        'icon-directions',
                        '0',
                        'module_lists' => array(
                            array('Manage', 'lane/manage', 1, 1), // (title, url, visible_in_sidebar, type = 0=system, 1=view, 2=add, 3=edit, 4=delete, 5=download)
                            array('Lane Listing', 'lane/search-data', 0, 1),
                            array('Download', 'lane/download', 0, 5),
                            array('Create', 'lane/create', 0, 2),
                            array('Edit', 'lane/edit', 0, 3),
                            array('Update', 'lane/update', 0, 3),
                            array('Detail', 'lane/detail', 0, 1),
                            array('Delete', 'lane/delete', 0, 4),
                            array('Group Action', 'lane/group-action', 0, 4),
                        )
                    ),

                )
            ),
            array(
                'Configuration',
                1,
                'module_titles' => array(
                    array(
                        'Settings',
                        '',
                        'fa fa-cog',
                        '0',
                        'module_lists' => array(
                            array('Manage', 'setting/manage', 1, 1),
                            array('Update Settings', 'setting/update', 0, 1)
                        )
                    ),
                    /*/
                    array(
                        'Navigation',
                        '',
                        'icon-direction',
                        '0',
                        'module_lists' => array(
                            array('Manage', 'navigation/settings', 1, 1),
                            array('Update Navigation', 'navigation/update-navigation-sorting', 0, 1),
                            array('Load Locations', 'navigation/load-module-locations', 0, 1),
                            array('Load List', 'navigation/load-module-lists', 0, 1),
                            array('Remove List', 'navigation/remove-list', 0, 1),
                            array('Remove List From Location', 'navigation/remove-list-from-location', 0, 1),
                            array('Hide List', 'navigation/hide-list', 0, 1),
                            array('Load Links', 'navigation/load-module-links', 0, 1),
                            array('Add list', 'navigation/add-list', 0, 1),
                            array('Add link to list', 'navigation/add-link-to-list', 0, 1),
                            array('Add page to list', 'navigation/add-page-to-list', 0, 1),
                            array('Add list to location', 'navigation/add-list-to-location', 0, 1),
                        )
                    ),
                    //*/
                )

            ),
        );


        if (isset($modules)) {
            foreach ($modules as $module) {

                /** Seed Sidebar Module Types */
                $module_type_id = DB::table('module_types')->insertGetId(
                    [
                        'title'       => $module[0],
                        'description' => $module[0] . ' Description.',
                        'active'      => $module[1],
                        'created_at'  => date('Y-m-d H:i:s'),
                        'updated_at'  => date('Y-m-d H:i:s')
                    ]
                );

                if (isset($module['module_titles'])) {
                    foreach ($module['module_titles'] as $title) {

                        $module_titles_title = $title[0];

                        /** Seed Sidebar Module Titles */
                        $module_title_id = DB::table('modules')->insertGetId(
                            [
                                'module_type_id'     => $module_type_id,
                                'parent'             => 0,
                                'title'              => $title[0],
                                'slug'               => str_slug($title[0]),
                                'description'        => $title[0] . ' Description.',
                                'url'                => $title[1],
                                'type'               => $title[3],
                                'icon'               => $title[2],
                                'visible_in_sidebar' => $title[0] == 'Systems' ? 0 : 1, // Systems modules wont visible in the sidebar
                                'active'             => 1,
                                'created_at'         => date('Y-m-d H:i:s'),
                                'updated_at'         => date('Y-m-d H:i:s')
                            ]
                        );

                        /** Seed Permissions */
                        DB::table('permissions')->insert(
                            [
                                'role_id'    => 1,
                                'module_id'  => $module_title_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ]
                        );

                        //region for test
                        if ($module_titles_title == 'Tickets') {
                            foreach ([2, 3, 4, 5, 6] as $role_id) {
                                DB::table('permissions')->insert(
                                    [
                                        'role_id'    => $role_id,
                                        'module_id'  => $module_title_id,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]
                                );
                            }
                        }
                        //endregion

                        if (isset($title['module_lists'])) {
                            foreach ($title['module_lists'] as $list) {

                                /** Seed Sidebar Module Lists */
                                $module_list_id = DB::table('modules')->insertGetId(
                                    [
                                        'module_type_id'     => $module_type_id,
                                        'parent'             => $module_title_id,
                                        'title'              => $list[0],
                                        'slug'               => str_slug($list[0]),
                                        'description'        => $list[0] . ' Description.',
                                        'url'                => $list[1],
                                        'type'               => $list[3],
                                        'icon'               => '',
                                        'visible_in_sidebar' => $list[2],
                                        'active'             => 1,
                                        'created_at'         => date('Y-m-d H:i:s'),
                                        'updated_at'         => date('Y-m-d H:i:s')
                                    ]
                                );

                                /** Seed Permissions */
                                DB::table('permissions')->insert(
                                    [
                                        'role_id'    => 1,
                                        'module_id'  => $module_list_id,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]
                                );

                                //region for test
                                if ($module_titles_title == 'Tickets') {
                                    foreach ([2, 3, 4, 5, 6] as $role_id) {
                                        DB::table('permissions')->insert(
                                            [
                                                'role_id'    => $role_id,
                                                'module_id'  => $module_list_id,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s')
                                            ]
                                        );
                                    }
                                }
                                //endregion

                            } // foreach
                        } // if


                    } // foreach
                } // if


            } // foreach
        } // if
    }
}
