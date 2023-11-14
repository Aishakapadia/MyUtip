<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Main
{
    use SoftDeletes;

    //region Module Fields
    /**
     * Set all the fields, to view in the listing or export into csv file.
     *
     * @var array
     */
    public static $module_fields = [
        'id'          => [
            'download'        => [
                'downloadable' => false,
                'map_field'    => '',
                'title'        => 'ID',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 's',
        ],
        'title'       => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Site Title',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 's',
        ],
        'site_type'        => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Site Type',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 'st',
        ],
        'material_type' => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Material Type',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 's',
        ],
        'sort'        => [
            'download'        => [
                'downloadable' => false,
                'map_field'    => '',
                'title'        => 'Sort',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 's',
        ],
        'active'      => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Status',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 's',
        ],
        'created_at'  => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Created At',
            ],
            'multiple'        => ['created_at_from', 'created_at_to'],
            'search_via_like' => false,
            'alias'           => 's',
        ],
    ];
    //endregion

    //region Relations

//    public function relationTickets()
//    {
//        return $this->hasMany(Ticket::class, 'site_id_from');
//    }

    public function relationTickets()
    {
        return $this->belongsToMany(Ticket::class, 'ticket_drop_off_sites');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
    //endregion

    public static function getSites($options = array(), $arrayFormat = false)
    {
        $fields = array_keys(self::$module_fields);

        $options = array_map(function ($e) {
            return is_scalar($e) ? trim($e) : $e;
        }, $options);

        $options['start'] = isset($options['start']) ? $options['start'] : 0;
        $options['length'] = isset($options['length']) ? $options['length'] : 25;
        $options['orderBy'] = isset($options['orderBy']) ? $options['orderBy'] : 's.id';
        $options['orderByDirection'] = isset($options['orderByDirection']) ? $options['orderByDirection'] : 'DESC';
        $options['filterBy'] = isset($options['filterBy']) ? $options['filterBy'] : false;

        // Setting orderBy field
        if ($fields[$options['order'][0]['column']]) {
            $options['orderBy'] = $fields[$options['order'][0]['column']];
            if (is_array($fields[$options['order'][0]['column']])) {
                $options['orderBy'] = key($options['orderBy']);
            }
        }

        $query = DB::table('sites AS s')
            ->join('site_types AS st', 'st.id', '=', 's.site_type_id')
            ->whereNull('s.deleted_at')
            ->select(
                [
                    's.id',
                    's.title',
                    //'s.slug',
                    's.description',
                    'st.title AS site_type',
                    's.material_type',
                    's.sort',
                    's.active',
                    's.created_at',
                ]
            );

        /**
         * Filters
         */
        if (self::$module_fields) {
            foreach (self::$module_fields as $key => $field) {

                // apply range filters
                if (!empty($field['multiple'])) {
                    if (array_key_exists($field['multiple'][0], $options) && $options[$field['multiple'][0]] != '') {
                        $query->where($field['alias'] . '.' . $key, '>=', date('Y-m-d 00:00:00', strtotime($options[$field['multiple'][0]])));
                    }

                    if (array_key_exists($field['multiple'][1], $options) && $options[$field['multiple'][1]] != '') {
                        $query->where($field['alias'] . '.' . $key, '<=', date('Y-m-d 23:59:00', strtotime($options[$field['multiple'][1]])));
                    }
                }

                // apply where or like filters
                if ($options[$key] != '') {
                    // apply like filter
                    if ($field['search_via_like']) {
                        if (array_key_exists($key, $options) && $options[$key] != '') {
                            $query->where($field['alias'] . '.' . $key, 'LIKE', '%' . $options[$key] . '%');
                        }
                    } else {
                        // apply where filter
                        if (array_key_exists($key, $options) && $options[$key] != '') {
                            $query->where($field['alias'] . '.' . $key, $options[$key]);
                        }
                    }
                }

            } // endforeach
        }

        // $page = Page::find(\Auth::page()->id);
        // if (!$page->isSuper()) {
        //     $query->where('u.role_id', '!=', \Config::get('constants.ROLE_ID_SUPER'));
        // }

        if (!$query) {
            return false;
        }

        $count = $query->count();

        if (!$count) {
            return ['total' => $count];
        }

        $query->orderBy($options['orderBy'], $options['orderByDirection']);

        if ($options['length'] && $options['length'] > 0) {
            $list = $query->skip($options['start'])->take($options['length'])->get();
        } else {
            $list = $query->get();
        }

        \Log::info(['vehicle_type manage query: ' => $query->toSql(), 'options' => $options, 'count' => $count, 'list' => $list]);

        return ['total' => $count, 'dataset' => $list];
    }

    public static function getSitesForDropDown()
    {
        return self::where('active', 1)
            ->whereNull('deleted_at')
            ->orderBy('title', 'ASC')
            ->pluck('title', 'id');
    }
}
