<?php namespace App\Http\Controllers\Backend;

use DB;
use App\User;
use Config;

class BackendDashboardController extends BackendController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getIndex()
    {
        $user = User::find(\Auth::user()->id);

        $total_requests = 0;
        $pendingForAdminApproval = 0;
        $completedRequests = 0;
        $rejectedByAdminRequests = 0;

        switch (\Auth::user()->role_id) {
            case Config::get('constants.ROLE_ID_SUPPLIER'):
                $tickets = DB::select('select ticket_id,max(status_id) as stat
                from status_ticket 
                join (select id from tickets where user_id = ' . $user->id . ' and deleted_at IS NULL) as t
                 on t.id = ticket_id
                  group by ticket_id
                ');
                $total_requests = count($tickets);
                foreach ($tickets as $ticket) {
                    if ($ticket->stat == Config::get('constants.OPEN_BY_SUPPLIER')) {
                        $pendingForAdminApproval += 1;
                    }
                    if ($ticket->stat == Config::get('constants.VEHICLE_OFFLOADED_BY_SITE_TEAM')) {
                        $completedRequests += 1;
                    }
                    if ($ticket->stat == Config::get('constants.CANCEL_BY_ADMIN')) {
                        $rejectedByAdminRequests += 1;
                    }
                }
                break;
            case Config::get('constants.ROLE_ID_SUPER'):
            case Config::get('constants.ROLE_ID_ADMIN'):
                $tickets = DB::select('select ticket_id,max(status_id) as stat,max(s.created_at)
                from status_ticket as s
                join (select id from tickets where deleted_at IS NULL) as t
                 on t.id = ticket_id
                group by ticket_id');
                $total_requests = count($tickets);
                foreach ($tickets as $ticket) {
                    if ($ticket->stat == Config::get('constants.OPEN_BY_SUPPLIER')) {
                        $pendingForAdminApproval += 1;
                    }
                    if ($ticket->stat == Config::get('constants.VEHICLE_OFFLOADED_BY_SITE_TEAM')) {
                        $completedRequests += 1;
                    }
                    if ($ticket->stat == Config::get('constants.CANCEL_BY_ADMIN')) {
                        $rejectedByAdminRequests += 1;
                    }
                }
                break;
            case Config::get('constants.ROLE_ID_SITE_TEAM'):
                $tickets = DB::select('select * from tickets 
                join (select * from site_user where user_id = ' . $user->id . ') as su
                on su.site_id = site_id_to
                join (select ticket_id,max(status_id) as stat from status_ticket GROUP by ticket_id) as st
                on st.ticket_id = id
                where deleted_at IS NULL
                group by id
                ');
                $total_requests = count($tickets);
                foreach ($tickets as $ticket) {
                    if ($ticket->stat == Config::get('constants.OPEN_BY_SUPPLIER')) {
                        $pendingForAdminApproval += 1;
                    }
                    if ($ticket->stat == Config::get('constants.VEHICLE_OFFLOADED_BY_SITE_TEAM')) {
                        $completedRequests += 1;
                    }
                    if ($ticket->stat == Config::get('constants.CANCEL_BY_ADMIN')) {
                        $rejectedByAdminRequests += 1;
                    }
                }
                break;
            case Config::get('constants.ROLE_ID_VIEWER'):
                break;
            case Config::get('constants.ROLE_ID_TRANSPORTER'):
                //$total_requests = count($user->transporterTickets());
                $total_requests = count(DB::select("SELECT DISTINCT(tt.ticket_id) FROM ticket_transporter AS tt
                JOIN transporter_user AS tu ON tt.transporter_id = tu.transporter_id
                JOIN tickets AS t ON tt.ticket_id = t.id
                JOIN status_ticket as st on tt.ticket_id = st.ticket_id
                WHERE tu.user_id = " . $user->id . " && t.deleted_at IS NULL && st.status_id > 2"));
                break;
            default:
                break;
        }

        $total = [
            'requests'                            => $total_requests != NULL ? $total_requests : 0,
            'requests_pending_for_admin_approval' => $pendingForAdminApproval != NULL ? $pendingForAdminApproval : 0,
            'requests_rejected_by_admin'          => $rejectedByAdminRequests != NULL ? $rejectedByAdminRequests : 0,
            'requests_completed'                  => $completedRequests != NULL ? $completedRequests : 0,
        ];

        return view(admin_view('dashboard'), compact('total'));
    }
}