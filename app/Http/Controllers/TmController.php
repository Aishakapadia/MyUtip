<?php

namespace App\Http\Controllers;

use App\Induction;
use App\Mail\InductionSurveyAssignedToTm;
use App\Mail\PopSurveyAssignedToTm;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Log;
use Illuminate\Http\Request;
use App\Pop;
use Auth;
use DB;

class TmController extends Controller
{
    public function getTmData(Request $request)
    {
        Log::notice('LOG-STARTED: ' . __FUNCTION__);
        Log::info(['INPUT: ' => $request->all()]);

        $distributor = $request->distributor;
        $email = $request->email;
        $password = $request->password;

        Log::info('Start Validation');

        //region Validations
        if ($distributor == null) {
            return response()->json([
                'success' => 0,
                'message' => 'Distributor parameter is required.'
            ]);
        }

        if ($email == null) {
            return response()->json([
                'success' => 0,
                'message' => 'Email parameter is required.'
            ]);
        }

        if ($password == null) {
            return response()->json([
                'success' => 0,
                'message' => 'Password parameter is required.'
            ]);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'User not found in the system.'
            ]);
        }

        //dd(Hash::check($password, $user->password));
        if (!Auth::attempt(['email' => $email, 'password' => $password], null)) {
            return response()->json([
                'success' => 0,
                'message' => 'Credentials not matched.'
            ]);
        }

        //endregion

        Log::info('Credentials Passed');

        $output = $this->getData($distributor, $email);
        Log::info('OUTPUT');
        Log::info($output);
        Log::notice('LOG-END: ' . __FUNCTION__);
        return $output;
    }

    public function getData($distributor, $email)
    {
        //$survey_list = Pop::getListForTmMobility($distributor, $email);
        $survey_list = Pop::getListForTmMobilityFinal($distributor, $email);
        $induction_list = Induction::getListForTmMobility($distributor, $email);

        return response()->json([
            'success'        => 1,
//            'survey_list_count'    => $survey_list->count(),
//            'induction_list_count' => count($induction_list),
            'survey_list'    => $survey_list,
            'induction_list' => $induction_list
        ]);
    }

    public function assignInductionToTm(Request $request)
    {
        $dayEndDate = $request->date ? $request->date : \Carbon\Carbon::now()->subDay(1)->toDateString();
        $distributor = $request->distributor;
        $tm_email = $request->email;

        Log::info('INDUCTION - TM data assignment started for ' . $dayEndDate);
//        Mail::to('muhammad.khalil2@unilever.com')->send(new InductionSurveyAssignedToTm($dayEndDate));
//        exit();

//        $query = \DB::table('inductions')
//            ->where('pop_channel_name_new', 'LMT')
//            ->where('pop_channel_name_new', 'B3')
//            ->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($dayEndDate)))
//            ->where('created_at', '<=', date('Y-m-d 23:59:00', strtotime($dayEndDate)));
//
//        if ($query->count()) {
//            $query->update(['verification_status_tm' => 3]);
//        }
//        Log::debug($query->toSql());
//        Log::debug($query->getBindings());

        if ($distributor != '' && $tm_email != '') {
            $data = DB::table('inductions')
                ->select('inductions.user_id', 'users.email', 'inductions.distributor', 'distributors.region')
                ->join('users', 'users.id', '=', 'inductions.user_id')
                ->join('distributors', 'distributors.distributor', '=', 'inductions.distributor')
                ->where('inductions.distributor', $distributor)
                ->where('users.email', $tm_email)
                ->groupBy('inductions.user_id', 'users.email', 'inductions.distributor', 'distributors.region')
                ->orderBy('users.email', 'ASC')->get();

            if ($data->count()) {
                $this->proceedInductionAssignment($data, $dayEndDate);
            }
        } else {

            $data = DB::table('inductions')
                ->select('inductions.user_id', 'users.email', 'inductions.distributor', 'distributors.region')
                ->join('users', 'users.id', '=', 'inductions.user_id')
                ->join('distributors', 'distributors.distributor', '=', 'inductions.distributor')
                ->groupBy('inductions.user_id', 'users.email', 'inductions.distributor', 'distributors.region')
                ->orderBy('users.email', 'ASC');

            $data->chunk(50, function ($rows) use ($dayEndDate) {
                if ($rows) {
                    $this->proceedInductionAssignment($rows, $dayEndDate);
                }
            });
        }

        //Mail::to('muhammad.khalil2@unilever.com')->send(new InductionSurveyAssignedToTm($dayEndDate));
        dump('INDUCTION - TM data assignment finished for ' . $dayEndDate);
        Log::info('INDUCTION - TM data assignment finished for ' . $dayEndDate);
    }

    private function proceedInductionAssignment($rows, $dayEndDate)
    {
        foreach ($rows as $row) {
            //dump($row);

            $query = DB::table('inductions AS i')
                ->where('i.user_id', $row->user_id)
                ->where('i.distributor', $row->distributor)
                ->where('i.pop_channel_name_new', '!=', 'B3')
                ->where('i.pop_channel_name_new', '!=', 'LMT')
                ->where('i.verification_status_tm', 0)
                ->where('i.created_at', '>=', date('Y-m-d 00:00:00', strtotime($dayEndDate)))
                ->where('i.created_at', '<=', date('Y-m-d 23:59:00', strtotime($dayEndDate)));

            Log::debug($query->toSql());
            Log::debug($query->getBindings());

            $queryToBeFiltered = clone $query;

            if ($query->get()->count() > 0) {
                $take = round($query->get()->count() / 100 * 20);
                $result = $query->get()->random($take);

                $filter_pops = [];
                if ($result->count()) {
                    foreach ($result as $x) {
                        $filter_pops[] = $x->pop_code;
                    }
                }

                $queryToBeFiltered->whereIn('pop_code', $filter_pops)->update(['verification_status_tm' => 3]);
            }
        }
    }

    public function assignClosedPopToTM(Request $request)
    {
        $dayEndDate = $request->date ? $request->date : \Carbon\Carbon::now()->subDay(1)->toDateString();

        Log::info('SURVEY - Closed POP - TM data assignment started for ' . $dayEndDate);

        Pop::where('pop_closed_permanently', 1)->update(['verification_status_tm' => 4]);

        Log::info('SURVEY - Closed POP - TM data assignment finished for ' . $dayEndDate);
    }

//    public function assignSurveyToTm(Request $request)
//    {
//
//        $dayEndDate = $request->date ? $request->date : \Carbon\Carbon::now()->subDay(1)->toDateString();
//        $distributor = $request->distributor;
//        Log::info('SURVEY - TM data assignment started for ' . $dayEndDate);
//
//        if ($distributor) {
//            $pop = Pop::select('pops.distributor', 'pops.dsr_code', 'pops.user_id')
//                ->join('users', 'users.id', '=', 'pops.user_id')
//                ->groupBy('pops.distributor', 'pops.dsr_code', 'pops.user_id')
//                ->where('pops.distributor', $distributor)
//                ->first();
//
//            $region = \DB::table('distributors')->where('distributor', $pop->distributor)->select('region')->first();
//            $sales_pops = \DB::table('sales')->where('region', $region->region)->pluck('pop_code');
//
//            $query = \DB::table('pops AS p')
//                ->where('p.user_id', $pop->user_id)
//                ->where('p.pop_closed_permanently', 0)
//                ->where('p.pop_closed_temporarily', 0)
//                ->where('p.verification_status_tm', 0)
//                ->where('p.dsr_code', $pop->dsr_code)
//                ->where('p.distributor', $pop->distributor)
//                ->where('p.created_at', '>=', date('Y-m-d 00:00:00', strtotime($dayEndDate)))
//                ->where('p.created_at', '<=', date('Y-m-d 23:59:00', strtotime($dayEndDate)));
//
////            dump($query->toSql());
////            dump($query->getBindings());
//
//            $queryBefore = clone $query;
//            $queryToBeFiltered = clone $query;
//
//            if ($query->count()) {
//
//                /**
//                 * Take all those pops, that does not have sales in 9 weeks
//                 */
//                $queryNotHaveSale = $query->whereNotIn('pop_code', $sales_pops);
//                $pops_needs_to_filter = [];
//                if ($queryNotHaveSale->count()) {
//                    foreach ($queryNotHaveSale->get() as $key => $row) {
//                        $pops_needs_to_filter[] = $row->pop_code;
//                    }
//                }
//
//                /**
//                 * Remove pops from the query, that have not data and take 20% from remaining data-set
//                 */
//                $queryBefore = $queryBefore->whereNotIn('pop_code', $pops_needs_to_filter);
//                $take = round($queryBefore->count() / 100 * 20);
//                $queryBefore = $queryBefore->get()->random($take);
//                $pops_needs_to_filter_remaining = [];
//                if ($queryBefore->count()) {
//                    foreach ($queryBefore as $key => $row) {
//                        $pops_needs_to_filter_remaining[] = $row->pop_code;
//                    }
//                }
//
//                /**
//                 * Pops that have no sales in 9 weeks
//                 * Pops remaining in the data-set only 20%
//                 */
//                $filter_pops = array_merge($pops_needs_to_filter, $pops_needs_to_filter_remaining);
//
//                //TODO::enable it
//                $queryToBeFiltered->whereIn('pop_code', $filter_pops)->update(['verification_status_tm' => 3]);
//            }
//
//            dd('done');
//        } else {
//            Pop::select('pops.distributor', 'pops.dsr_code', 'pops.user_id')
//                ->join('users', 'users.id', '=', 'pops.user_id')
//                ->groupBy('pops.distributor', 'pops.dsr_code', 'pops.user_id')
//                ->orderBy('pops.distributor')
//                ->chunk(5, function ($pops) use ($dayEndDate) {
//                    foreach ($pops as $pop) {
//
//                        $region = \DB::table('distributors')->where('distributor', $pop->distributor)->select('region')->first();
//                        $sales_pops = \DB::table('sales')->where('region', $region->region)->pluck('pop_code');
//
//
//                        $query = \DB::table('pops AS p')
//                            ->where('p.user_id', $pop->user_id)
//                            ->where('p.pop_closed_permanently', 0)
//                            ->where('p.pop_closed_temporarily', 0)
//                            ->where('p.verification_status_tm', 0)
//                            ->where('p.dsr_code', $pop->dsr_code)
//                            ->where('p.distributor', $pop->distributor)
//                            ->where('p.created_at', '>=', date('Y-m-d 00:00:00', strtotime($dayEndDate)))
//                            ->where('p.created_at', '<=', date('Y-m-d 23:59:00', strtotime($dayEndDate)));
//
//                        Log::debug($query->toSql());
//                        Log::debug($query->getBindings());
//
//                        $queryBefore = clone $query;
//                        $queryToBeFiltered = clone $query;
//
//                        if ($query->count()) {
//
//                            /**
//                             * Take all those pops, that does not have sales in 9 weeks
//                             */
//                            $queryNotHaveSale = $query->whereNotIn('pop_code', $sales_pops);
//                            $pops_needs_to_filter = [];
//                            if ($queryNotHaveSale->count()) {
//                                foreach ($queryNotHaveSale->get() as $key => $row) {
//                                    $pops_needs_to_filter[] = $row->pop_code;
//                                }
//                            }
//
//                            /**
//                             * Remove pops from the query, that have not data and take 20% from remaining data-set
//                             */
//                            $queryBefore = $queryBefore->whereNotIn('pop_code', $pops_needs_to_filter);
//                            $take = round($queryBefore->count() / 100 * 20);
//                            $queryBefore = $queryBefore->get()->random($take);
//                            $pops_needs_to_filter_remaining = [];
//                            if ($queryBefore->count()) {
//                                foreach ($queryBefore as $key => $row) {
//                                    $pops_needs_to_filter_remaining[] = $row->pop_code;
//                                }
//                            }
//
//                            /**
//                             * Pops that have no sales in 9 weeks
//                             * Pops remaining in the data-set only 20%
//                             */
//                            $filter_pops = array_merge($pops_needs_to_filter, $pops_needs_to_filter_remaining);
//
//                            //TODO::enable it
//                            $queryToBeFiltered->whereIn('pop_code', $filter_pops)->update(['verification_status_tm' => 3]);
//                        }
//                    }
//                });
//        }
//
////        Mail::to('muhammad.khalil2@unilever.com')->send(new PopSurveyAssignedToTm($dayEndDate));
//        dump('done');
//
//        Log::info('SURVEY - TM data assignment finished for ' . $dayEndDate);
//    }

    public function assignSurveyToTm(Request $request)
    {
        $dayEndDate = $request->date ? $request->date : \Carbon\Carbon::now()->subDay(1)->toDateString();
        $distributor = $request->distributor;
        $tm_email = $request->email;

        Log::info('SURVEY - TM data assignment started for ' . $dayEndDate);

        if ($distributor != '' && $tm_email != '') {
            $data = DB::table('pops')
                ->select('pops.user_id', 'users.email', 'pops.distributor', 'distributors.region')
                ->join('users', 'users.id', '=', 'pops.user_id')
                ->join('distributors', 'distributors.distributor', '=', 'pops.distributor')
                ->where('pops.distributor', $distributor)
                ->where('users.email', $tm_email)
                ->groupBy('pops.user_id', 'users.email', 'pops.distributor', 'distributors.region')
                ->orderBy('users.email', 'ASC')->get();

            if ($data->count()) {
                $this->proceedSurveyAssignment($data, $dayEndDate);
            }

        } else {
            $data = DB::table('pops')
                ->select('pops.user_id', 'users.email', 'pops.distributor', 'distributors.region')
                ->join('users', 'users.id', '=', 'pops.user_id')
                ->join('distributors', 'distributors.distributor', '=', 'pops.distributor')
                ->groupBy('pops.user_id', 'users.email', 'pops.distributor', 'distributors.region')
                ->orderBy('users.email', 'ASC');

            $data->chunk(100, function ($rows) use ($dayEndDate) {
                if ($rows) {
                    $this->proceedSurveyAssignment($rows, $dayEndDate);
                }
            });
        }

        dump('SURVEY - TM data assignment finished for ' . $dayEndDate);
        Log::info('SURVEY - TM data assignment finished for ' . $dayEndDate);
    }

    private function proceedSurveyAssignment($rows, $dayEndDate)
    {
        foreach ($rows as $row) {
            //dump($row);
            $sales_pops = \DB::table('sales')->where('region', $row->region)->pluck('pop_code');

            $query = DB::table('pops AS p')
                ->where('p.user_id', $row->user_id)
                ->where('p.distributor', $row->distributor)
                ->where('p.pop_closed_permanently', 0)
                ->where('p.pop_closed_temporarily', 0)
                ->where('p.verification_status_tm', 0)
                ->where('p.created_at', '>=', date('Y-m-d 00:00:00', strtotime($dayEndDate)))
                ->where('p.created_at', '<=', date('Y-m-d 23:59:00', strtotime($dayEndDate)));

            Log::debug($query->toSql());
            Log::debug($query->getBindings());

            $queryBefore = clone $query;
            $queryToBeFiltered = clone $query;

            /**
             * Take all those pops, that does not have sales in 9 weeks
             */
            $queryNotHaveSale = $query->whereNotIn('pop_code', $sales_pops);
            $pops_needs_to_filter = [];
            if ($queryNotHaveSale->count()) {
                foreach ($queryNotHaveSale->get() as $key => $row) {
                    $pops_needs_to_filter[] = $row->pop_code;
                }
            }

            /**
             * Remove pops from the query, that have not data and take 20% from remaining data-set
             */
            $queryBefore = $queryBefore->whereNotIn('pop_code', $pops_needs_to_filter);
            $take = round($queryBefore->get()->count() / 100 * 20);
            $queryBefore = $queryBefore->get()->random($take);
            $pops_needs_to_filter_remaining = [];
            if ($queryBefore->count()) {
                foreach ($queryBefore as $key => $row) {
                    $pops_needs_to_filter_remaining[] = $row->pop_code;
                }
            }

            /**
             * Pops that have no sales in 9 weeks
             * Pops remaining in the data-set only 20%
             */
            $filter_pops = array_merge($pops_needs_to_filter, $pops_needs_to_filter_remaining);

            //TODO::enable it
            $queryToBeFiltered->whereIn('pop_code', $filter_pops)->update(['verification_status_tm' => 3]);
        }
    }
}
