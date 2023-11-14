<?php

namespace App\Http\Controllers;

use DB;
use App\Induction;
use Illuminate\Http\Request;
use Log;

class InductionController extends Controller
{
    public function tmResponse(Request $request)
    {
        Log::notice('LOG-STARTED: ' . __FUNCTION__);
        Log::info(['INPUT: ' => $request->all()]);
        $data = json_decode($request->data, true);
        Log::alert($data);

        if ($data) {
            //foreach ($data as $row) {
            //Log::alert($row);

            $induction = Induction::find($data['id']);
            $induction->verification_status_tm = $data['status'];
            $induction->verification_updated_at_tm = $data['updated_at'];
            $induction->comments_tm = $data['comment'];
            $induction->tm_latitude = $data['latitude'];
            $induction->tm_longitude = $data['longitude'];
            $induction->tm_accuracy = $data['accuracy'];
            $induction->tm_provider = $data['provider'];
            $induction->save();

            Log::info(['TM-RESPONSE SAVED' => $induction]);
            //}
        }

        Log::notice('LOG-END: ' . __FUNCTION__);

        return response()->json([
            'success' => 1,
            'message' => 'tm response uploaded successfully.'
        ]);
    }

}
