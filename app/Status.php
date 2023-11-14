<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Main
{
    use SoftDeletes;

    //region Relations
    public function relationTickets()
    {
        return $this->belongsToMany('App\Ticket');
    }
    //endregion

    public static function getDropDown()
    {
        return self::where('active', 1)
            ->whereNull('deleted_at')
            ->orderBy('id', 'ASC')
            ->pluck('visible AS title', 'id');
    }

}
