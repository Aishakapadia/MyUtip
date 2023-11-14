<?php

namespace App\Http\Middleware;

use App\Ticket;
use App\User;
use Closure;

class CheckTicketDetailPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allowed = false;

        if (!\Auth::user())
            return redirect(admin_url('login'));

        $user = User::find(\Auth::user()->id);
        $ticket = Ticket::find($request->segment(4));
        $currentStatus = $ticket->relationActiveStatus()->id;
//        dump($user->id);
//        dump($ticket);
//        dump($currentStatus);
//        dump($user->isTransporter());
//        dump(Ticket::isTicketApprovedByAdmin($ticket));
//        dd($ticket->isTicketApprovedForYou($ticket, $user));

        if ($ticket) {

            if ($user->isTransporter()) {
                if (Ticket::isTicketApprovedByAdmin($ticket)) {
                    if ($currentStatus >= \Config::get('constants.CONFIRM_TRANSPORTER_BY_ADMIN')) {

                        //TODO:: check transporter approved by admin is you or not?
                        if ($ticket->isTicketApprovedForYou($ticket, $user)) {
                            $allowed = true;
                        }

                    } else {
                        $allowed = true;
                    }
                }
            }

            if ($user->isSiteTeam()) {
                if ($user->sites()->count()) {
                    foreach ($user->sites as $item) {
                        if ($ticket->site_id_to == $item->id) {
                            $allowed = true;
                        }
                    }
                }
            }

            if ($user->isSuper() || $user->isAdmin() || $user->isSupplier() || $user->isViewer() ) {
                $allowed = true;
            }

            if ($allowed) {
                return $next($request);
            }

        }


        return response("Insufficient Permissions", 401);
    }
}
