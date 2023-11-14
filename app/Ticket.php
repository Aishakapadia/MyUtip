<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Main
{
    use SoftDeletes;

    //region Module Fields
    /**
     * Set all the fields, to view in the listing or export into csv file.
     *
     * @var array
     */
    public static $module_fields = [
        'id'                      => [
            'download'        => [
                'downloadable' => false,
                'map_field'    => '',
                'title'        => 'ID',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 't',
            'column_name'     => 'id',
        ],
        'ticket_number'           => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Ticket Number',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 't',
            'column_name'     => 'ticket_number',
        ],
        'vehicle_type'            => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Vehicle Type',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 'vt',
            'column_name'     => 'title',
        ],
        'site_from'               => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Site From',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 'sf',
            'column_name'     => 'id',
        ],
        'site_to'                 => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Site To',
            ],
            'multiple'        => [],
            'search_via_like' => false,
            'alias'           => 'st',
            'column_name'     => 'id',
        ],
        'transporter'             => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Transporter',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 'tt',
            'column_name'     => 'title',
        ],
        'vehicle_number'          => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Vehicle Number',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 't',
            'column_name'     => 'vehicle_number',
        ],
        'driver_contact'          => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Driver Contact',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 't',
            'column_name'     => 'driver_contact',
        ],
        'eta'                     => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'ETA',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 't',
            'column_name'     => 'eta',
        ],
        'transporter_status'      => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Transporter Status',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 't',
            'column_name'     => 'id',
        ],
        'delivery_challan_number' => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Delivery Challan Number',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 't',
            'column_name'     => 'delivery_challan_number',
        ],
        'ticket_status' => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Ticket Status',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 't',
            'column_name'     => 'id',
        ],
        'remarks'       => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Remarks',
            ],
            'multiple'        => [],
            'search_via_like' => true,
            'alias'           => 't',
            'column_name'     => 'remarks',
        ],
        'created_at'    => [
            'download'        => [
                'downloadable' => true,
                'map_field'    => '',
                'title'        => 'Created At',
            ],
            'multiple'        => ['created_at_from', 'created_at_to'],
            'search_via_like' => false,
            'alias'           => 't',
            'column_name'     => 'created_at',
        ],
    ];
    //endregion

    //region Relations
    public function details()
    {
        return $this->hasMany('App\TicketDetail')
            ->select(
                [
                    'ticket_details.material_id',
                    //'ticket_details.material_type',
                    'ticket_details.quantity',
                    'ticket_details.weight',
                    'ticket_details.po_number',
                    'ticket_details.ibd_number',
                    'ticket_details.unit_id',
                    'materials.title AS material',
                    'materials.type AS material_type',
                    'materials.sap_code AS material_code',
                    'units.title AS unit',
                ]
            )
            ->join('materials', 'materials.id', '=', 'ticket_details.material_id')
            ->join('units', 'units.id', '=', 'ticket_details.unit_id');
    }

    public function relationStatuses()
    {
        return $this->belongsToMany(Status::class)
            ->withPivot('user_id', 'comments')
            ->withTimestamps();
    }

    public function relationInitiator()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Being used in ticket detail page for statuses history.
     *
     * @return $this
     */
    public function relationStatusesWithDetail()
    {
        return $this->belongsToMany(Status::class)
            ->select(
                'users.name',
                'roles.title AS role',
                'status_ticket.comments',
                'status_ticket.created_at',
                'status_ticket.updated_at',
                'statuses.icon',
                'statuses.visible',
                'statuses.title'
            )
            ->join('users', 'users.id', '=', 'status_ticket.user_id')
            ->join('roles', 'roles.id', '=', 'users.role_id');
    }

    public function relationActiveStatus()
    {
        $user = User::find(\Auth::user()->id);

        if ($user->isTransporter()) {
            $query = $this->belongsToMany(Status::class)
                ->where('user_id', $user->id);

            if ($query->count()) {
                $output = $this->belongsToMany(Status::class)
                    ->orderBy('id', 'DESC')
                    ->first();
            } else {
                $output = $this->belongsToMany(Status::class)
                    ->where('status_id', \Config::get('constants.APPROVE_BY_ADMIN'))
                    ->orderBy('id', 'DESC')
                    ->first();
            }

        } else {
            $output = $this->belongsToMany(Status::class)
                ->orderBy( 'id', 'DESC')
                ->first();
        }

        return $output;
    }

    public function relationVehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function relationFromSite()
    {
        return $this->belongsTo(Site::class, 'site_id_from');
    }

    public function relationToSite()
    {
        return $this->belongsTo(Site::class, 'site_id_to');
    }

    public function relationTransporters()
    {
        return $this->belongsToMany(Transporter::class, 'ticket_transporter')
//            ->select(
//                [
//                    'transporter_statuses.title AS transporterStatusTitle',
//                    'transporters.title'
//                ]
//            )
//            ->join('transporter_statuses',  'transporter_statuses.id', '=', 'ticket_transporter.transporter_status_id')
            ->withPivot('vehicle_number', 'driver_contact', 'eta', 'transporter_status_id', 'confirmed');
    }

    public function relationDropOffSites()
    {
        return $this->belongsToMany(Site::class, 'ticket_drop_off_sites');
    }

    public function getTicketDropOffSiteListAttribute()
    {
        return $this->relationDropOffSites()->pluck('site_id')->toArray();
    }

//    public function relationTransporter()
//    {
//        return $this->belongsTo(Transporter::class, 'transporter_id');
//    }

    public function files()
    {
        return $this->hasMany(TicketFile::class);
    }

    //endregion

    public static function isTicketApprovedByAdmin(Ticket $ticket)
    {
        //SELECT * FROM tickets t
        //INNER JOIN status_ticket sst ON(sst.ticket_id = t.id)
        //WHERE sst.status_id >= 3
        //GROUP BY t.id

        $query = self::select('sst.ticket_id')
            ->where('tickets.id', $ticket->id)
            ->where('sst.status_id', '>=', \Config::get('constants.APPROVE_BY_ADMIN'))
            ->join('status_ticket AS sst', 'sst.ticket_id', '=', 'tickets.id')
            ->groupBy('sst.ticket_id')
            ->get();

        if ($query->count()) {
            return true;
        }
    }

    public function isTicketApprovedForYou(Ticket $ticket, User $user = null)
    {
        $user = $user ? $user : \App\User::find(\Auth::user()->id);
        if ($user->isTransporter()) {
            $transporter = $user->transporters->first();

            $data = $this->relationTransporters()
                ->where('transporter_id', $transporter->id)
                ->where('ticket_id', $ticket->id)
                ->first();

            if ($data->pivot->transporter_status_id == \Config::get('constants.ACCEPTED_BY_ADMIN') || $data->pivot->transporter_status_id == \Config::get('constants.ACCEPTED_BY_SUPPLIER')) {
                return true;
            }
        }
    }

    public static function getTickets($options = array(), $arrayFormat = false)
    {
        $fields = array_keys(self::$module_fields);

        $options = array_map(function ($e) {
            return is_scalar($e) ? trim($e) : $e;
        }, $options);

        $options['start'] = isset($options['start']) ? $options['start'] : 0;
        $options['length'] = isset($options['length']) ? $options['length'] : 25;
        $options['orderBy'] = isset($options['orderBy']) ? $options['orderBy'] : 'td.id';
        $options['orderByDirection'] = isset($options['orderByDirection']) ? $options['orderByDirection'] : 'DESC';
        $options['filterBy'] = isset($options['filterBy']) ? $options['filterBy'] : false;

        // Setting orderBy field
        if ($fields[$options['order'][0]['column']]) {
            $options['orderBy'] = $fields[$options['order'][0]['column']];
            if (is_array($fields[$options['order'][0]['column']])) {
                $options['orderBy'] = key($options['orderBy']);
            }
        }

        $query = DB::table('tickets AS t')
            ->join('vehicle_types AS vt', 'vt.id', '=', 't.vehicle_type_id')
            ->join('sites AS sf', 'sf.id', '=', 't.site_id_from')
            ->join('sites AS st', 'st.id', '=', 't.site_id_to')
            //->join('transporters AS tt', 'tt.id', '=', 't.transporter_id')
            ->whereNull('t.deleted_at')
            ->select(
                [
                    't.id',
                    't.ticket_number',
                    'vt.title AS vehicle_type',
                    'sf.title AS site_from',
                    'st.title AS site_to',
//                    't.id AS material_type',

                    't.id AS transporter',
                    't.id AS vehicle_number',
                    't.id AS driver_contact',
                    't.id AS eta',
                    't.id AS transporter_status',
                    't.id AS ticket_status',

//                    't.vehicle_number',
//                    't.driver_contact',
                    't.id AS po_number',
                    't.remarks',
                    't.delivery_challan_number',
                    't.created_at',
                    't.draft',
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
                        $query->where($field['alias'] . '.' . $field['column_name'], '>=', date('Y-m-d 00:00:00', strtotime($options[$field['multiple'][0]])));
                    }

                    if (array_key_exists($field['multiple'][1], $options) && $options[$field['multiple'][1]] != '') {
                        $query->where($field['alias'] . '.' . $field['column_name'], '<=', date('Y-m-d 23:59:00', strtotime($options[$field['multiple'][1]])));
                    }
                }

                // Special Case for this Ticket Module.
                if ($key == 'ticket_status') {

                    if ($options[$key] != '') {
                        $query->join('status_ticket AS sst', 'sst.ticket_id', '=', 't.id');
                        //$query->where('sst.status_id', '=', $options[$key]);
                        $query->groupBy('sst.ticket_id');
                        $query->havingRaw("MAX(CASE WHEN sst.status_id = $options[$key] THEN sst.id END) = MAX(sst.id)");
                    }

                } else {
                    // apply where or like filters
                    if ($options[$key] != '') {
                        // apply like filter
                        if ($field['search_via_like']) {
                            if (array_key_exists($key, $options) && $options[$key] != '') {
                                $query->where($field['alias'] . '.' . $field['column_name'], 'LIKE', '%' . $options[$key] . '%');
                            }
                        } else {
                            // apply where filter
                            if (array_key_exists($key, $options) && $options[$key] != '') {
                                $query->where($field['alias'] . '.' . $field['column_name'], $options[$key]);
                            }
                        }
                    }
                }

            } // endforeach
        }

        $user = User::find(\Auth::user()->id);

        // if ($user->role_id == \Config::get('constants.ROLE_ID_ADMIN')) {
        //     $query->where('t.draft', '=', 0);
        // }

        if ($user->role_id == \Config::get('constants.ROLE_ID_SUPPLIER')) {
            $query->where('t.user_id', $user->id);
        }

        if ($user->role_id == \Config::get('constants.ROLE_ID_TRANSPORTER')) {
            $transporter_id = $user->transporters->first()->id;

            $query->join('status_ticket AS sst', 'sst.ticket_id', '=', 't.id');
            $query->where('sst.status_id', '>=', \Config::get('constants.APPROVE_BY_ADMIN'));

            //TODO:: get only those ticket which has been approved by admin and those which has not been approved for this transporter.
            $query->join('ticket_transporter AS tt', 'tt.ticket_id', '=', 't.id');
            $query->where('tt.transporter_id', $transporter_id);
            //$query->where('sst.status_id', '<', \Config::get('constants.VEHICLE_APPROVED_BY_SUPPLIER'));

            $query->groupBy('sst.ticket_id');
            \Log::info(['ticket manage query for transporters: ' => $query->toSql()]);
        }

        if ($user->role_id == \Config::get('constants.ROLE_ID_SITE_TEAM')) {
            $query->join('site_user AS su', 'su.site_id', '=', 't.site_id_to');
            $query->where('su.user_id', $user->id);
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


        \Log::info(['ticket manage query: ' => $query->toSql(), 'options' => $options, 'count' => $count, 'list' => $list]);

        return ['total' => $count, 'dataset' => $list];
    }

}
