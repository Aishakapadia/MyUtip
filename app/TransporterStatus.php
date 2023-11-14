<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransporterStatus extends Main
{
    use SoftDeletes;

    //protected $table = 'transporter_statuses';

    //region Relations
    public function relationTicketTransporters() {
        return $this->hasMany('App\TicketTransporter');
    }
    //endregion
}
