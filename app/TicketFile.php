<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketFile extends Main
{
    //region Relations
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    //endregion
}
