<?php

namespace App\Http\Controllers;

use DB;
use App\Induction;
use App\Pop;
use Illuminate\Http\Request;
use Log;
use Validator;

class PopController extends Controller
{
    public function postSurvey(Request $request)
    {
//        Log::warning('Hit on old version (PostSurvey)');
//
//        return response()->json([
//            'success' => 0,
//            'message' => 'Please install latest version.'
//        ]);


        Log::notice('LOG-STARTED: ' . __FILE__ . ' - ' . __FUNCTION__);
        Log::info(['INPUT: ' => $request->all()]);
        $data = json_decode($request->data, true);
        Log::alert($data);

        if (isset($data['POP_SURVEY']) && $data['POP_SURVEY']) {

            foreach ($data['POP_SURVEY'] as $row) {
                Log::alert(['LOOP: POP_SURVEY: ' => $row]);

                $popCode = $row['pop_code'];
                $popCodeEx = explode('-', $popCode);
                $town = $popCodeEx[0];
                $distributor = $popCodeEx[1];

                /**
                 * if pop-code exist in the db and it is temp-closed as true, then remove that pop and save new-one.
                 * TODO::remove temp-closed image file.
                 */
                //DB::table('pops')->where('pop_code', $popCode)->where('pop_closed_temporarily', 1)->delete();
                $popExist = DB::table('pops AS p')->where(function ($q) use ($popCode) {

                    $q->where(function ($query) use ($popCode) {
                        $query->where('p.pop_code', $popCode);
                        $query->where('p.verification_status_tm', 2);
                    })->orWhere(function ($query) use ($popCode) {
                        $query->where('p.pop_code', $popCode);
                        $query->where('p.pop_closed_temporarily', 1);
                    });

                })->where('p.distributor', $distributor)->first();

                if ($popExist) {
                    DB::table('pops')->where('id', $popExist->id)->delete();
                }

                $pop = new Pop();
                $pop->user_id = $row['tm_code'];
                $pop->distributor = $distributor;
                $pop->dsr_code = $row['dsr_code'];
                $pop->pjp_code = $row['pjp_code'];
                $pop->dsr_name = $row['dsr_name'];
                $pop->pop_code = $popCode;
                $pop->pop_name = $row['pop_name'];
                $pop->pop_rename = $row['pop_rename'];
                $pop->doc_date = date('Y-m-d H:i:s', strtotime($row['doc_date']));

                # Exclusive data
                //$pop->pop_town = $town;
                $pop->pop_section_code = $row['pop_section_code'];
                $pop->pop_section_name = $row['pop_section_name'];

                $pop->locality_code_old = $row['locality_code_old'];
                $pop->locality_name_old = $row['locality_name_old'];
                $pop->locality_code_new = $row['locality_code_new'];
                $pop->locality_name_new = $row['locality_name_new'];

                $pop->sub_locality_code_old = $row['sub_locality_code_old'];
                $pop->sub_locality_name_old = $row['sub_locality_name_old'];
                $pop->sub_locality_code_new = $row['sub_locality_code_new'];
                $pop->sub_locality_name_new = $row['sub_locality_name_new'];

                $pop->pop_channel_code_old = $row['pop_channel_code_old'];
                $pop->pop_channel_name_old = $row['pop_channel_name_old'];
                $pop->pop_channel_code_new = $row['pop_channel_code_new'];
                $pop->pop_channel_name_new = $row['pop_channel_name_new'];

                $pop->area_type_code_old = $row['area_type_code_old'];
                $pop->area_type_name_old = $row['area_type_name_old'];
                $pop->area_type_code_new = $row['area_type_code_new'];
                $pop->area_type_name_new = $row['area_type_name_new'];

                $pop->pop_address = $row['pop_address'];

                $pop->latitude = $row['latitude'];
                $pop->longitude = $row['longitude'];
                $pop->accuracy = $row['accuracy'];
                $pop->provider = $row['provider'];

                $pop->retailer_name = $row['retailer_name'];
                $pop->retailer_contact = $row['retailer_contact'];
                $pop->retailer_nic = $row['retailer_nic'];

                $pop->pop_closed_permanently = $row['pop_closed_permanently'] ? 1 : 0;
                $pop->pop_closed_temporarily = $row['pop_closed_temporarily'] ? 1 : 0;

                $pop->photo_signboard = $row['photo_signboard'];
                $pop->photo_counter = $row['photo_counter'];

                //$pop->verified_at = '';

                $pop->survey_started_at = $row['survey_started_at'];
                $pop->survey_finished_at = $row['survey_finished_at'];

                $pop->created_at = date('Y-m-d H:i:s');
                $pop->updated_at = date('Y-m-d H:i:s');

                $pop->save();
                Log::info(['pop saved' => $pop]);
            }
        }

        if (isset($data['POP_INDUCTION']) && $data['POP_INDUCTION']) {
            foreach ($data['POP_INDUCTION'] as $row) {
                Log::alert(['LOOP: POP_INDUCTION: ' => $row]);

                $induction = new Induction();
                //$popCode = $row['pop_code'];
                //$popCodeEx = explode('-', $popCode);
                //$town = $popCodeEx[0];

                $induction->user_id = $row['tm_code'];
                $distributor = $row['distributor'];

                $induction->distributor = $distributor;
                $induction->dsr_code = $row['dsr_code'];
                $induction->pjp_code = $row['pjp_code'];
                $induction->dsr_name = $row['dsr_name'];
                $induction->pop_code = $row['pop_code'];
                $induction->pop_name = $row['pop_name'];
                $induction->doc_date = date('Y-m-d H:i:s', strtotime($row['doc_date']));
                //$induction->pop_rename = '';

                if (isset($row['category_id'])) {
                    $ids = [];
                    foreach (explode(',', $row['category_id']) as $item) {
                        $ids[] = $item;
                    }
                }
                //$new_pop->category_id = $row['category_id'];

                # Exclusive data
                //$new_pop->pop_town = $town;
                $induction->pop_section_code = $row['pop_section_code'];
                $induction->pop_section_name = $row['pop_section_name'];

                $induction->locality_code_new = $row['locality_code_new'];
                $induction->locality_name_new = $row['locality_name_new'];

                $induction->sub_locality_code_new = $row['sub_locality_code_new'];
                $induction->sub_locality_name_new = $row['sub_locality_name_new'];

                $induction->pop_channel_code_new = $row['pop_channel_code_new'];
                $induction->pop_channel_name_new = $row['pop_channel_name_new'];

                $induction->area_type_code_new = $row['area_type_code_new'];
                $induction->area_type_name_new = $row['area_type_name_new'];

                $induction->pop_address = $row['pop_address'];

                $induction->latitude = $row['latitude'];
                $induction->longitude = $row['longitude'];
                $induction->accuracy = $row['accuracy'];
                $induction->provider = $row['provider'];

                $induction->retailer_name = $row['retailer_name'];
                $induction->retailer_contact = $row['retailer_contact'];
                $induction->retailer_nic = $row['retailer_nic'];

                $induction->photo_signboard = $row['photo_signboard'];
                $induction->photo_counter = $row['photo_counter'];

                $induction->survey_started_at = $row['survey_started_at'];
                $induction->survey_finished_at = $row['survey_finished_at'];

                $induction->created_at = date('Y-m-d H:i:s');
                $induction->updated_at = date('Y-m-d H:i:s');

                $induction->save();

                $induction->categories()->attach($ids);

                Log::info(['new pop saved' => $induction]);
            }
        }

        Log::notice('LOG-END: ' . __FILE__ . ' - ' . __FUNCTION__);

        return response()->json([
            'success' => 1,
            'message' => 'data uploaded successfully.'
        ]);
    }

    public function upload(Request $request)
    {
        Log::warning('Hit on old version (Upload)');

        return response()->json([
            'success' => 0,
            'message' => 'Please install latest version.'
        ]);

        Log::notice('LOG-STARTED: ' . __FILE__ . ' - ' . __FUNCTION__);
        Log::info(['INPUT: ' => $request->all()]);

        $destinationPath = public_path('/uploads/images');

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        if (!$validator->passes()) {
            Log::alert($validator->errors()->all());
            //return response()->json(['error' => $validator->errors()->all()]);
        }

        $image = $request->file('image');
        $file_name = $image->getClientOriginalName();

        Log::debug($file_name);

        /** Update Database with Photos */
        //*//
        if ($file_name) {

            $file = explode('_', $file_name);
            $f = explode('-', $file_name);
            $r = explode('.', end($f));
            Log::alert(['ex-by-underscore' => $file, 'ex-by-dash' => $f, 'ex-by-dot' => $r]);
            $pop = Pop::where('pop_code', $file[1])->first();

            if ($r[0] == 'INDUCTION') {
                $pop = Induction::where('pop_code', $file[1])->first();
            }

            if ($pop) {
                if ($file[0] == 'SBP') { // Signboard
                    $pop->photo_signboard = $file_name;
                }
                if ($file[0] == 'CEP') { // Counter
                    $pop->photo_counter = $file_name;
                }
            }
            Log::alert($pop);
            $pop->save();
        }
        //*/

        try {
            $ii = $image->move($destinationPath, $file_name);
            Log::alert($ii);
            $response = response()->json([
                'success' => 1,
                'message' => 'Image -> ' . $file_name . ' uploaded successfully.'
            ]);
            Log::alert($file_name . ' file uploaded successfully');
        } catch (\Exception $e) {
            $response = response()->json(['error' => $e->getMessage()]);
            Log::alert($file_name . ' file not uploaded');
        }

        Log::info($response);
        Log::notice('LOG-END: ' . __FILE__ . ' - ' . __FUNCTION__);
        return $response;

    }


    public function postDsrSurvey(Request $request)
    {
        $output = [];
        $output['pop_survey'] = 0;
        $output['pop_induction'] = 0;
        $output['images_uploaded'] = 0;
        $output['images_message'] = 0;
        $file_name_counter_image = '';
        $file_name_signboard_image = '';


        Log::notice('LOG-STARTED: ' . __FILE__ . ' - ' . __FUNCTION__);
        Log::info(['INPUT: ' => $request->all()]);
        $data = json_decode($request->data, true);
        Log::alert($data);

        //region Validation
        if (!$data['POP_SURVEY']) {
            $output['success'] = 0;
            $output['message'] = 'Pop survey data not found';
        }
        //endregion

        //region POP_SURVEY
        if ($data['POP_SURVEY']) {
            $output['pop_survey'] = 1;

            $data = $data['POP_SURVEY'][0];
            Log::alert(['POP_SURVEY: ' => $data]);
            //dump($data);

            $popCode = $data['pop_code'];
            $popCodeEx = explode('-', $popCode);
            $town = $popCodeEx[0];
            $distributor = $popCodeEx[1];
            $pop_closed_permanently = $data['pop_closed_permanently'];
            $pop_closed_temporarily = $data['pop_closed_temporarily'];

            // Both Images Should Be Present
            if ($pop_closed_permanently == 0 && $pop_closed_temporarily == 0) {
                //region UPLOAD-FILES
                $destinationPath = public_path('/uploads/images');

                $validator = Validator::make($request->all(), [
                    'image_counter'   => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                    'image_signboard' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                ]);

                if (!$validator->passes()) {
                    Log::alert($validator->errors()->all());
                    return response()->json([
                        'success' => 0,
                        'message' => $validator->errors()->all()
                    ]);
                }

                $image_counter = $request->file('image_counter');
                $image_signboard = $request->file('image_signboard');

                $file_name_counter = $image_counter->getClientOriginalName();
                $file_name_signboard = $image_signboard->getClientOriginalName();

                try {
                    $moved_counter_image = $image_counter->move($destinationPath, $file_name_counter);
                    $moved_signboard_image = $image_signboard->move($destinationPath, $file_name_signboard);
                } catch (\Exception $e) {
                    $output['images_uploaded'] = 0;
                    $output['images_message'] = $e->getMessage();
                }

                $file_name_counter_image = $file_name_counter;
                $file_name_signboard_image = $file_name_signboard;

                $output['images_uploaded'] = 1;
                $output['images_message'] = 'Image: ' . $file_name_counter . ' & ' . $file_name_signboard . ' have been saved';
                //endregion
            }

            /**
             * if pop-code exist in the db and it is temp-closed as true, then remove that pop and save new-one.
             * TODO::remove temp-closed image file.
             */
            //DB::table('pops')->where('pop_code', $popCode)->where('pop_closed_temporarily', 1)->delete();
            $popExist = DB::table('pops AS p')->where(function ($q) use ($popCode) {

                $q->where(function ($query) use ($popCode) {
                    $query->where('p.pop_code', $popCode);
                    $query->where('p.verification_status_tm', 2);
                })->orWhere(function ($query) use ($popCode) {
                    $query->where('p.pop_code', $popCode);
                    $query->where('p.pop_closed_temporarily', 1);
                });

            })->where('p.distributor', $distributor)->first();

            if ($popExist) {
                DB::table('pops')->where('id', $popExist->id)->delete();
            }

            $same_data = DB::table('pops')
                ->where('pop_code', $popCode)
                ->where('distributor', $distributor)
                ->where('pjp_code', $data['pjp_code'])
                ->where('dsr_code', $data['dsr_code']);

            if ($same_data->count() > 1) {
                $same_data_before = clone $same_data;
                $top = $same_data->first();
                $same_data_before->where('id', '!=', $top->id)->delete();
            }


            // Save Pop-Survey
            $pop = new Pop();
            $pop->user_id = $data['tm_code'];
            $pop->distributor = $distributor;
            $pop->dsr_code = $data['dsr_code'];
            $pop->pjp_code = $data['pjp_code'];
            $pop->dsr_name = $data['dsr_name'];
            $pop->pop_code = $popCode;
            $pop->pop_name = $data['pop_name'];
            $pop->pop_rename = $data['pop_rename'];
            //TODO::enable it
            $pop->doc_date = date('Y-m-d H:i:s', strtotime($data['doc_date']));

            # Exclusive data
            //$pop->pop_town = $town;
            $pop->pop_section_code = $data['pop_section_code'];
            $pop->pop_section_name = $data['pop_section_name'];

            $pop->locality_code_old = $data['locality_code_old'];
            $pop->locality_name_old = $data['locality_name_old'];
            $pop->locality_code_new = $data['locality_code_new'];
            $pop->locality_name_new = $data['locality_name_new'];

            $pop->sub_locality_code_old = $data['sub_locality_code_old'];
            $pop->sub_locality_name_old = $data['sub_locality_name_old'];
            $pop->sub_locality_code_new = $data['sub_locality_code_new'];
            $pop->sub_locality_name_new = $data['sub_locality_name_new'];

            $pop->pop_channel_code_old = $data['pop_channel_code_old'];
            $pop->pop_channel_name_old = $data['pop_channel_name_old'];
            $pop->pop_channel_code_new = $data['pop_channel_code_new'];
            $pop->pop_channel_name_new = $data['pop_channel_name_new'];

            $pop->area_type_code_old = $data['area_type_code_old'];
            $pop->area_type_name_old = $data['area_type_name_old'];
            $pop->area_type_code_new = $data['area_type_code_new'];
            $pop->area_type_name_new = $data['area_type_name_new'];

            $pop->pop_address = $data['pop_address'];

            $pop->latitude = $data['latitude'];
            $pop->longitude = $data['longitude'];
            $pop->accuracy = $data['accuracy'];
            $pop->provider = $data['provider'];
            $pop->device_info = $request->device_info;

            $pop->retailer_name = $data['retailer_name'];
            $pop->retailer_contact = $data['retailer_contact'];
            $pop->retailer_nic = $data['retailer_nic'];

            $pop->pop_closed_permanently = $pop_closed_permanently;
            $pop->pop_closed_temporarily = $pop_closed_temporarily;

            $pop->verification_status_tm = $pop_closed_permanently ? '3' : '0';

            $pop->photo_signboard = $file_name_signboard_image;
            $pop->photo_counter = $file_name_counter_image;

            $pop->survey_started_at = date('Y-m-d H:i:s', strtotime($data['survey_started_at']));
            $pop->survey_finished_at = date('Y-m-d H:i:s', strtotime($data['survey_finished_at']));

            $pop->created_at = date('Y-m-d H:i:s');
            $pop->updated_at = date('Y-m-d H:i:s');

            if ($pop->save()) {
                $output['success'] = 1;
                $output['message'] = $pop->pop_code . ' has been saved.';
                Log::info(['pop saved' => $pop]);
            }

        }
        //endregion

        //region POP_INDUCTION
        if (isset($data['POP_INDUCTION']) && $data['POP_INDUCTION']) {
            if (isset($data['POP_INDUCTION'][0])) {
                $data = $data['POP_INDUCTION'][0];
                Log::alert(['POP_INDUCTION: ' => $data]);

                $output['pop_induction'] = 1;

                //region UPLOAD-FILES
                $destinationPath = public_path('/uploads/images');

                $validator = Validator::make($request->all(), [
                    'image_counter'   => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                    'image_signboard' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                ]);

                if (!$validator->passes()) {
                    Log::alert($validator->errors()->all());
                    return response()->json([
                        'success' => 0,
                        'message' => $validator->errors()->all()
                    ]);
                }

                $image_counter = $request->file('image_counter');
                $image_signboard = $request->file('image_signboard');

                $file_name_counter = $image_counter->getClientOriginalName();
                $file_name_signboard = $image_signboard->getClientOriginalName();

                try {
                    $moved_counter_image = $image_counter->move($destinationPath, $file_name_counter);
                    $moved_signboard_image = $image_signboard->move($destinationPath, $file_name_signboard);
                } catch (\Exception $e) {
                    $output['images_uploaded'] = 0;
                    $output['images_message'] = $e->getMessage();
                }

                $file_name_counter_image = $file_name_counter;
                $file_name_signboard_image = $file_name_signboard;

                $output['images_uploaded'] = 1;
                $output['images_message'] = 'Image: ' . $file_name_counter . ' & ' . $file_name_signboard . ' have been saved';
                //endregion


                $induction = new Induction();
                //$popCode = $row['pop_code'];
                //$popCodeEx = explode('-', $popCode);
                //$town = $popCodeEx[0];

                $induction->user_id = $data['tm_code'];
                $distributor = $data['distributor'];

                $induction->distributor = $distributor;
                $induction->dsr_code = $data['dsr_code'];
                $induction->pjp_code = $data['pjp_code'];
                $induction->dsr_name = $data['dsr_name'];
                $induction->pop_code = $data['pop_code'];
                $induction->pop_name = $data['pop_name'];
                //TODO::enable it
                $induction->doc_date = date('Y-m-d H:i:s', strtotime($data['doc_date']));

                # Exclusive data
                //$new_pop->pop_town = $town;
                $induction->pop_section_code = $data['pop_section_code'];
                $induction->pop_section_name = $data['pop_section_name'];

                $induction->locality_code_new = $data['locality_code_new'];
                $induction->locality_name_new = $data['locality_name_new'];

                $induction->sub_locality_code_new = $data['sub_locality_code_new'];
                $induction->sub_locality_name_new = $data['sub_locality_name_new'];

                $induction->pop_channel_code_new = $data['pop_channel_code_new'];
                $induction->pop_channel_name_new = $data['pop_channel_name_new'];

                $induction->area_type_code_new = $data['area_type_code_new'];
                $induction->area_type_name_new = $data['area_type_name_new'];

                $induction->pop_address = $data['pop_address'];

                $induction->latitude = $data['latitude'];
                $induction->longitude = $data['longitude'];
                $induction->accuracy = $data['accuracy'];
                $induction->provider = $data['provider'];
                $induction->device_info = $request->device_info;

                $induction->retailer_name = $data['retailer_name'];
                $induction->retailer_contact = $data['retailer_contact'];
                $induction->retailer_nic = $data['retailer_nic'];

                $induction->photo_signboard = $file_name_signboard_image;
                $induction->photo_counter = $file_name_counter_image;

                $induction->survey_started_at = date('Y-m-d H:i:s', strtotime($data['survey_started_at']));
                $induction->survey_finished_at = date('Y-m-d H:i:s', strtotime($data['survey_finished_at']));

                $induction->created_at = date('Y-m-d H:i:s');
                $induction->updated_at = date('Y-m-d H:i:s');

                switch ($data['pop_channel_name_new']) {
                    case 'B3':
                        $status_tm = 3;
                        break;

                    case 'LMT':
                        $status_tm = 3;
                        break;

                    default:
                        $status_tm = 0;
                        break;
                }

                $induction->verification_status_tm = $status_tm;

                if ($induction->save()) {
                    $output['success'] = 1;
                    $output['message'] = $induction->pop_code . ' has been saved';
                    Log::info(['pop saved' => $induction]);
                }

                if (isset($data['category_id'])) {
                    $ids = [];
                    foreach (explode(',', $data['category_id']) as $item) {
                        $ids[] = $item;
                    }
                }

                $induction->categories()->attach($ids);

                Log::info(['new pop saved' => $induction]);
            }
        }
        //endregion


        Log::info('OUTPUT:');
        Log::info($output);
        Log::notice('LOG-END: ' . __FILE__ . ' - ' . __FUNCTION__);

        return response()->json($output);
    }

    public function tmResponse(Request $request)
    {
        Log::notice('LOG-STARTED: ' . __FILE__ . ' - ' . __FUNCTION__);
        Log::info(['INPUT: ' => $request->all()]);
        $data = json_decode($request->data, true);
        Log::alert($data);

        if ($data) {
            /**
             * 1) Check total quantity(%) of current data that TM is rejecting.
             * 2) If TM is already rejected this DSR's 50% data, then all the survey will be rejected.
             */

            $pop = Pop::find($data['id']);

            if (Pop::rejectAllPops($pop)) {
                Log::notice('LOG-END: ' . __FILE__ . ' - ' . __FUNCTION__);
                return response()->json([
                    'success'  => 2,
                    'dsr_code' => $pop->dsr_code,
                    'pjp_code' => '',
                    'message'  => 'This DSR 50% data has been marked as rejected, so all the worked has to be done properly again.'
                ]);
            }

            $pop->verification_status_tm = $data['status'];
            $pop->comments_tm = $data['comment'];
            $pop->verification_updated_at_tm = $data['updated_at'];
            $pop->tm_latitude = $data['latitude'];
            $pop->tm_longitude = $data['longitude'];
            $pop->tm_accuracy = $data['accuracy'];
            $pop->tm_provider = $data['provider'];
            $pop->save();

            Log::info(['TM-RESPONSE SAVED' => $pop]);
        }

        Log::notice('LOG-END: ' . __FILE__ . ' - ' . __FUNCTION__);

        return response()->json([
            'success'  => 1,
            'dsr_code' => $pop->dsr_code,
            'pjp_code' => '',
            'message'  => 'tm response uploaded successfully.'
        ]);
    }

}
