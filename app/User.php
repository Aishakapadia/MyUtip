<?php namespace App;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Set all the fields, to view in the listing or export into csv file.
     *
     * @var array
     */
    public static $module_fields = [
        'id'         => [
            'download'        => [
                'downloadable' => false,
                'map_field'    => '',
                'title'        => 'ID',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 'u',
        ],
        'role_id'    => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => 'role_title',
                'title'        => 'Role',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 'u',
        ],
        'name'       => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'User Name',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 'u',
        ],
        'email'      => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Email',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 'u',
        ],
        'mobile'     => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Contact #',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 'u',
        ],
        'active'     => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Status',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 'u',
        ],
        'created_at' => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Created At',
            ],
            'multiple'        => ['created_at_from', 'created_at_to'],
            'search_via_like' => false,
            'alias'           => 'u',
        ],
    ];

    //region Relations
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function transporters()
    {
        return $this->belongsToMany('App\Transporter');
    }

    public function sites()
    {
        return $this->belongsToMany('App\Site');
    }

    /**
     * Ticket initiated by
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userTickets()
    {
        return $this->hasMany('App\Ticket');
    }

    /**
     * Ticket has been assigned to many transporters.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function transporterTickets()
    {
        return $this->belongsToMany('App\Ticket', 'ticket_user');
    }

    //endregion

    public function getUserTransporterListAttribute()
    {
        return $this->transporters()->pluck('id')->toArray();
    }

    public function getUserSiteListAttribute()
    {
        return $this->sites()->pluck('id')->toArray();
    }

    public function isSuper()
    {
        return $this->role()->where('id', \Config::get('constants.ROLE_ID_SUPER'))->exists();
    }

    public function isAdmin()
    {
        return $this->role()->where('id', \Config::get('constants.ROLE_ID_ADMIN'))->exists();
    }

    public function isSupplier()
    {
        return $this->role()->where('id', \Config::get('constants.ROLE_ID_SUPPLIER'))->exists();
    }

    public function isTransporter()
    {
        return $this->role()->where('id', \Config::get('constants.ROLE_ID_TRANSPORTER'))->exists();
    }

    public function isViewer()
    {
        return $this->role()->where('id', \Config::get('constants.ROLE_ID_VIEWER'))->exists();
    }

    public function isSiteTeam()
    {
        return $this->role()->where('id', \Config::get('constants.ROLE_ID_SITE_TEAM'))->exists();
    }

    public function hasPermissions($role_id)
    {
        return $permissions = Permission::where('role_id', $role_id)->get();
    }

    public static function getUsers($options = array(), $arrayFormat = false)
    {
        $fields = array_keys(self::$module_fields);

        $options = array_map(function ($e) {
            return is_scalar($e) ? trim($e) : $e;
        }, $options);

        $options['start'] = isset($options['start']) ? $options['start'] : 0;
        $options['length'] = isset($options['length']) ? $options['length'] : 25;
        $options['orderBy'] = isset($options['orderBy']) ? $options['orderBy'] : 'u.id';
        $options['orderByDirection'] = isset($options['orderByDirection']) ? $options['orderByDirection'] : 'DESC';
        $options['filterBy'] = isset($options['filterBy']) ? $options['filterBy'] : false;

        // Setting orderBy field
        if ($fields[$options['order'][0]['column']]) {
            $options['orderBy'] = $fields[$options['order'][0]['column']];
            if (is_array($fields[$options['order'][0]['column']])) {
                $options['orderBy'] = key($options['orderBy']);
            }
        }

        $query = DB::table('users AS u')
            ->join('roles AS r', 'r.id', '=', 'u.role_id')
            ->where('u.id', '!=', 1)// except Super-User
            ->whereNull('u.deleted_at')
            ->select([
                    'r.title AS role_title',
                    'u.id',
                    'u.role_id',
                    'u.name',
                    'u.email',
                    'u.mobile',
                    //'u.date_of_birth',
                    'u.sort',
                    'u.active',
                    'u.created_at',
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

        $user = User::find(\Auth::user()->id);
        if (!$user->isSuper()) {
//            $query->where('u.role_id', '!=', \Config::get('constants.ROLE_ID_SUPER'));
//            $query->where('u.role_id', '!=', \Config::get('constants.ROLE_ID_ADMIN'));
            //$query->where('u.role_id', '>', $user->role_id);
        }

//        if ($user->role_id == \Config::get('constants.ROLE_ID_CCD')) {
//            $query->where('u.role_id', '=', \Config::get('constants.ROLE_ID_TM'));
//        }

//        if (\Request::get('institute_id')) {
//            $query->join('user_institutes AS ui', 'ui.user_id', '=', 'u.id');
//            $query->where('ui.institute_id', \Request::get('institute_id'));
//        }

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

        \Log::info(['user manage query: ' => $query->toSql(), 'options' => $options, 'count' => $count, 'list' => $list]);

        return ['total' => $count, 'dataset' => $list];
    }

}
