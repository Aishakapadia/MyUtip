<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transporter extends Main
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
            'alias'           => 't',
        ],
        'title'       => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Title',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 't',
        ],
//        'slug'        => [
//            'download'        => [
//                'downloadable' => true,
//                'map_field'    => '',
//                'title'        => 'Slug',
//            ],
//            'multiple'        => [],
//            'search_via_like' => true,
//            'alias'           => 't',
//        ],
        'description' => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Description',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 't',
        ],
        'sort'        => [
            'download'        => [
                'downloadable' => false,
                'map_field'    => '',
                'title'        => 'Sort',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 't',
        ],
        'active'      => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Status',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 't',
        ],
        'created_at'  => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Created At',
            ],
            'multiple'        => ['created_at_from', 'created_at_to'],
            'search_via_like' => false,
            'alias'           => 't',
        ],
    ];
    //endregion

    //region Relations
    public function lanes()
    {
        return $this->belongsToMany('App\Lane');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function relationTickets()
    {
        return $this->belongsToMany(Ticket::class, 'ticket_transporter');
    }
    //endregion

    public static function getTransporters($options = array(), $arrayFormat = false)
    {
        $fields = array_keys(self::$module_fields);

        $options = array_map(function ($e) {
            return is_scalar($e) ? trim($e) : $e;
        }, $options);

        $options['start'] = isset($options['start']) ? $options['start'] : 0;
        $options['length'] = isset($options['length']) ? $options['length'] : 25;
        $options['orderBy'] = isset($options['orderBy']) ? $options['orderBy'] : 't.id';
        $options['orderByDirection'] = isset($options['orderByDirection']) ? $options['orderByDirection'] : 'DESC';
        $options['filterBy'] = isset($options['filterBy']) ? $options['filterBy'] : false;

        // Setting orderBy field
        if ($fields[$options['order'][0]['column']]) {
            $options['orderBy'] = $fields[$options['order'][0]['column']];
            if (is_array($fields[$options['order'][0]['column']])) {
                $options['orderBy'] = key($options['orderBy']);
            }
        }

        $query = DB::table('transporters AS t')
            //->join('roles AS r', 'r.id', '=', 'u.role_id')
            ->whereNull('t.deleted_at')
            ->select(
                [
                    't.id',
                    't.title',
//                    't.slug',
                    't.description',
                    't.sort',
                    't.active',
                    't.created_at',
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

    public static function getTransportersForDropDown()
    {
        return self::where('active', 1)
            ->whereNull('deleted_at')
            ->orderBy('title', 'ASC')
            ->pluck('title', 'id');
    }

    public static function getTransportersWhoHasBid(Ticket $ticket)
    {
        $query = DB::table('transporters AS t')
            ->select([
                'tt.transporter_id',
                'u.name'
            ])
            ->join('ticket_transporter AS tt', 'tt.transporter_id', '=', 't.id')
            ->join('transporter_user AS tu', 'tu.transporter_id', '=', 'tt.transporter_id')
            ->join('users AS u', 'u.id', '=', 'tu.user_id')
            ->where('tt.transporter_status_id', \Config::get('constants.BID_SUBMITTED'))
            ->where('tt.ticket_id', $ticket->id)
            ->get();

        return $query;
    }
}
