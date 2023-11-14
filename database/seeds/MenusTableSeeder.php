<?php

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            [
                'Header',
//                'list' => [
                //                    [
                //                        0, // parent
                //                        'About', // title
                //                        'about', // slug
                //                        '', // url
                //                    ],
                //                    [
                //                        0, // parent
                //                        'Privacy Policy', // title
                //                        'privacy-policy', // slug
                //                        '', // url
                //                    ]
                //                ]
            ],
            [
                'Footer',
//                'list' => [
                //                    [
                //                        0, // parent
                //                        'Contact', // title
                //                        'contact', // slug
                //                        '', // url
                //                    ]
                //                ]
            ],
        ];
        foreach ($locations as $location) {

            $location_id = DB::table('menu_locations')->insertGetId(
                [
                    'title'      => $location[0],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            );

            if (isset($location['list'])) {
                foreach ($location['list'] as $list) {
                    $list_id = DB::table('menu_lists')->insertGetId(
                        [
                            'parent'     => $list[0],
                            'title'      => $list[1],
                            'page_slug'  => $list[2],
                            'url'        => $list[3],
                            'ready'      => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                    DB::table('menu_location_list')->insert(
                        [
                            'menu_location_id' => $location_id,
                            'menu_list_id'     => $list_id,
                        ]
                    );
                }
            }
        }

    }
}
