<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
    //region Relations
    public function ticket()
    {
        return $this->belongsTo('App\Ticket', 'ticket_id');
    }
    //endregion
}
