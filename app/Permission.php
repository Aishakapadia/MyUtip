<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Permission extends Main
{
    public static function getPermissions($options = array(), $arrayFormat = false)
    {
        $options = array_map(function ($e) {
            return is_scalar($e) ? trim($e) : $e;
        }, $options);

        $options['start'] = isset($options['start']) ? $options['start'] : 0;
        $options['length'] = isset($options['length']) ? $options['length'] : 25;
        $options['orderBy'] = isset($options['orderBy']) ? $options['orderBy'] : 'u.id';
        $options['orderByDirection'] = isset($options['orderByDirection']) ? $options['orderByDirection'] : 'DESC';
        $options['filterBy'] = isset($options['filterBy']) ? $options['filterBy'] : false;
        $options['income_type'] = isset($options['income_type']) ? $options['income_type'] : false;

        switch ($options['orderBy']) {
            case 'title':
                $options['orderBy'] = 'm.title';
                break;
            case 'email':
                $options['orderBy'] = 'm.email';
                break;
            case 'status':
                $options['orderBy'] = 'm.active';
                break;
            case 'created_at':
                $options['orderBy'] = 'm.created_at';
                break;
        }

        $query = DB::table('modules AS m')
            //->join('countries AS c', 'c.id', '=', 'ud.country_id')
            ->whereNull('m.deleted_at')
            ->select([
                    'm.id',
                    'm.parent',
                    'm.title',
                    'm.slug',
                    'm.description',
                    'm.url',
                    'm.icon',
                    'm.active',
                    'm.created_at'
                ]
            );

        // data filter
        //dd($options);

        if (!is_null($options['title']) && $options['title'] != "") {
            $query->where('title', 'LIKE', '%' . $options['title'] . '%');
        }
        if (!is_null($options['active']) && $options['active'] !== '') {
            $query->where('active', $options['active']);
        }

        #/ created from
        if (array_key_exists('date_from', $options) && $options['date_from'] != '') {
            $query->where('u.created_at', '>=', date('Y-m-d 00:00:00', strtotime($options['date_from'])));
        }

        #/ created to
        if (array_key_exists('date_to', $options) && $options['date_to'] != '') {
            $query->where('u.created_at', '<=', date('Y-m-d 23:59:00', strtotime($options['date_to'])));
        }

        //dd ( $options );

        if (!$query) {
            return false;
        }

        $count = $query->count();

        if (!$count) {
            return ['total' => $count];
        }

        $query->orderBy($options['orderBy'], $options['orderByDirection']);

        \Log::info(['permission manage query: ' => $query->toSql(), 'options' => $options]);

        if ($options['length'] && $options['length'] > 0) {
            $list = $query->skip($options['start'])->take($options['length'])->get();
        } else {
            $list = $query->get();
        }

        return ['total' => $count, 'dataset' => $list];
    }
}
