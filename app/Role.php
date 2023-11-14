<?php namespace App;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Main
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
            'alias'           => 'r',
        ],
        'title'       => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Title',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 'r',
        ],
        'slug'        => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Slug',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 'r',
        ],
        'description' => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Description',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 'r',
        ],
        'sort'        => [
            'download'        => [
                'downloadable' => false,
                'map_field'    => '',
                'title'        => 'Sort',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 'r',
        ],
        'active'      => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Status',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 'r',
        ],
        'created_at'  => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Created At',
            ],
            'multiple'        => ['created_at_from', 'created_at_to'],
            'search_via_like' => false,
            'alias'           => 'r',
        ],
    ];
    //endregion

    //region Relations
    public function roles()
    {
        return $this->hasMany('\App\Role');
    }

    public function modules()
    {
        return $this->belongsToMany('App\Module', 'permissions', 'role_id', 'module_id');
    }

    //endregion

    public static function getRolesExceptSuper()
    {
        $query = DB::table('roles')->where('id', '!=', 1);

        if (\Auth::check()) {
            $user = \App\User::find(\Auth::user()->id);
            if ($user->role_id == \Config::get('constants.ROLE_ID_CCD')) {
                $query = $query->where('id', \Config::get('constants.ROLE_ID_TM'));
            }
        }

        $query = $query->where('active', 1)->orderBy('title', 'ASC')->pluck('title', 'id');

        return $query;
    }

//    public static function getRolesExcept($except = [])
//    {
//        return self::whereNotIn('id', $except)
//            ->where('active', 1)
//            ->orderBy('title', 'ASC')
//            ->pluck('title', 'id');
//    }

    public static function getRoles($options = array(), $arrayFormat = false)
    {
        $fields = array_keys(self::$module_fields);

        $options = array_map(function ($e) {
            return is_scalar($e) ? trim($e) : $e;
        }, $options);

        $options['start'] = isset($options['start']) ? $options['start'] : 0;
        $options['length'] = isset($options['length']) ? $options['length'] : 25;
        $options['orderBy'] = isset($options['orderBy']) ? $options['orderBy'] : 'r.id';
        $options['orderByDirection'] = isset($options['orderByDirection']) ? $options['orderByDirection'] : 'DESC';
        $options['filterBy'] = isset($options['filterBy']) ? $options['filterBy'] : false;

        // Setting orderBy field
        if ($fields[$options['order'][0]['column']]) {
            $options['orderBy'] = $fields[$options['order'][0]['column']];
            if (is_array($fields[$options['order'][0]['column']])) {
                $options['orderBy'] = key($options['orderBy']);
            }
        }

        $query = DB::table('roles AS r')
            //->join('roles AS r', 'r.id', '=', 'u.role_id')
            ->where('r.id', '!=', 1)// except Super
            ->whereNull('r.deleted_at')
            ->select([
                'r.id',
                'r.title',
                'r.slug',
                'r.description',
                'r.sort',
                'r.active',
                'r.created_at',
            ]);

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

        $user = User::find(\Auth::user()->id);
        if (!$user->isSuper()) {
            $query->where('r.id', '!=', \Config::get('constants.ROLE_ID_SUPER'));
        }

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

        \Log::info(['role manage query: ' => $query->toSql(), 'options' => $options, 'count' => $count, 'list' => $list]);

        return ['total' => $count, 'dataset' => $list];
    }

}
