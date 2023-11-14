<?php

namespace App\Http\Controllers;

use App\Category;
use App\DsrLogin;
use App\User;
use Log;
use Illuminate\Http\Request;
use App\AreaType;
use App\Channel;
use App\Pop;
use App\UltraPop;
use App\ControlParam;
use App\Section;
use App\Locality;
use App\SubLocality;
use App\Distributor;
use DB;

class DsrController extends Controller
{
    public function getDsrData(Request $request)
    {
        Log::notice('LOG-STARTED: ' . __FUNCTION__);
        Log::info(['INPUT: ' => $request->all()]);
        /**
         * 1) Validate required parameters
         * 2) Get DSR Data from Android Ultra database.
         * 3) Delete coming distributor data from respective tables and insert new data.
         */

        $distributor = $request->distributor;
        $DSRcode = $request->DSRCode;
        $PJPcode = $request->pjp;
        $password = $request->password;

        Log::info('Start Validation');

        //region Validations
        if ($distributor == null) {
            return response()->json([
                'success' => 0,
                'message' => 'distributor parameter is required.'
            ]);
        }

        if ($DSRcode == null) {
            return response()->json([
                'success' => 0,
                'message' => 'DSRcode parameter is required.'
            ]);
        }

        if ($PJPcode == null) {
            return response()->json([
                'success' => 0,
                'message' => 'PJPcode parameter is required.'
            ]);
        }

//        if ($password == null) {
//            return response()->json([
//                'success' => 0,
//                'message' => 'password parameter is required.'
//            ]);
//        }
        //endregion

//        $dsrLogin = new DsrLogin();
//        $dsrLogin->distributor = $distributor;
//        $dsrLogin->dsr = $DSRcode;
//        $dsrLogin->pjp = $PJPcode;
//        $dsrLogin->password = $password;
//        $dsrLogin->type = 1;
//        $dsrLogin->latitude = $request->latitude;
//        $dsrLogin->longitude = $request->longitude;
//        $dsrLogin->timestamp = date(strtotime('Y-m-d H:i:s', $request->timestamp));
//        $dsrLogin->save();

        Log::info('Credentials Passed');

        $str = json_decode(file_get_contents('http://www.androultra.com/api/fetch/getDsrDatanew?pjp=' . $PJPcode . '&password=' . $password . '_%21%40%23%24&DSRCode=' . $DSRcode . '&distributor=' . $distributor), true);
        Log::alert(['get-dsr-data: ' => $str]);

        switch ($str['success']) {
            case 2:
                $message = 'Invalid credentials.';
                break;

            case 3:
                $message = 'Day-End already completed in Ultra Mobility.';
                break;

            default:
                $message = '';
                break;
        }

        if ($str['success'] == "1") {

            //region control_param

            ControlParam::where('distributor', $distributor)
                ->where('PJPcode', $PJPcode)
                ->where('DSRcode', $DSRcode)
                ->delete();

            if (isset($str['control_param'][0])) {

                $cp_data = $str['control_param'][0];

                $control_param = new ControlParam();
                $control_param->FILEPATH = $cp_data['FILEPATH'];
                $control_param->order_sku_limit = $cp_data['order_sku_limit'];
                $control_param->BACKGROUND_GPS = $cp_data['BACKGROUND_GPS'];
                $control_param->rec_Type = $cp_data['rec_Type'];
                $control_param->cmnum = $cp_data['cmnum'];
                $control_param->avnum = $cp_data['avnum'];
                $control_param->docdate = $cp_data['docdate'];
                $control_param->PJPcode = $cp_data['PJPcode'];
                $control_param->DSRcode = $cp_data['DSRcode'];
                $control_param->DSR_password = $cp_data['DSR_password'];
                $control_param->sell_category = $cp_data['sell_category'];
                $control_param->GST_free_sku = $cp_data['GST_free_sku'];
                $control_param->gst_sku_discount = $cp_data['gst_sku_discount'];
                $control_param->gst_group_discount = $cp_data['gst_group_discount'];
                $control_param->gst_total_discount = $cp_data['gst_total_discount'];
                $control_param->Order_qty_check = $cp_data['Order_qty_check'];
                $control_param->Delivery_qty_check = $cp_data['Delivery_qty_check'];
                $control_param->company = $cp_data['company'];
                $control_param->distributor = $cp_data['distributor'];
                $control_param->warehouse = $cp_data['warehouse'];
                $control_param->visit_type = $cp_data['visit_type'];
                $control_param->ask_gst = $cp_data['ask_gst'];
                $control_param->delv_date = $cp_data['delv_date'];
                $control_param->dsr_credit = $cp_data['dsr_credit'];
                $control_param->dsr_credit_allowed = $cp_data['dsr_credit_allowed'];
                $control_param->hht_version = $cp_data['hht_version'];
                $control_param->bonusbeforaftergst = $cp_data['bonusbeforaftergst'];
                $control_param->DistChkAsset = $cp_data['DistChkAsset'];
                $control_param->SellChkAsset = $cp_data['SellChkAsset'];
                $control_param->Scheme_basis = $cp_data['Scheme_basis'];
                $control_param->scheme_freesku = $cp_data['scheme_freesku'];
                $control_param->Scheme_discount = $cp_data['Scheme_discount'];
                $control_param->Edit_deliveredCM = $cp_data['Edit_deliveredCM'];
                $control_param->DB_initialize = $cp_data['DB_initialize'];
                $control_param->gst_free_sku_bonus = $cp_data['gst_free_sku_bonus'];
                $control_param->gst_sku_discount_bonus = $cp_data['gst_sku_discount_bonus'];
                $control_param->gst_group_discount_bonus = $cp_data['gst_group_discount_bonus'];
                $control_param->gst_total_discount_bonus = $cp_data['gst_total_discount_bonus'];
                $control_param->scheme_skusplit = $cp_data['scheme_skusplit'];
                $control_param->working_days = $cp_data['working_days'];
                $control_param->Chk_credit = $cp_data['Chk_credit'];
                $control_param->Companyname = $cp_data['Companyname'];
                $control_param->Working_Units = $cp_data['Working_Units'];
                $control_param->GST_caption = $cp_data['GST_caption'];
                $control_param->GST_caption1 = $cp_data['GST_caption1'];
                $control_param->GST_caption2 = $cp_data['GST_caption2'];
                $control_param->GST_caption3 = $cp_data['GST_caption3'];
                $control_param->DistributorName = $cp_data['DistributorName'];
                $control_param->DistributorGSTNumber = $cp_data['DistributorGSTNumber'];
                $control_param->Printer_id = $cp_data['Printer_id'];
                $control_param->Dsr_nme = $cp_data['Dsr_nme'];
                $control_param->LPPC = $cp_data['LPPC'];
                $control_param->fast_order = $cp_data['fast_order'];
                $control_param->U1 = $cp_data['U1'];
                $control_param->U2 = $cp_data['U2'];
                $control_param->U3 = $cp_data['U3'];
                $control_param->SCHEME_ON = $cp_data['SCHEME_ON'];
                $control_param->Automatic_Upload = $cp_data['Automatic_Upload'];
                $control_param->Scheme_onDate = $cp_data['Scheme_onDate'];
                $control_param->Price_Change = $cp_data['Price_Change'];
                $control_param->Default_cmgst = $cp_data['Default_cmgst'];
                $control_param->FCS_Module = $cp_data['FCS_Module'];
                $control_param->Bar_Code_Status = $cp_data['Bar_Code_Status'];
                $control_param->GPS_Status = $cp_data['GPS_Status'];
                $control_param->Stock_Checking = $cp_data['Stock_Checking'];
                $control_param->Check_Assortment = $cp_data['Check_Assortment'];
                $control_param->IQ_PROJECT_VERSION = $cp_data['IQ_PROJECT_VERSION'];
                $control_param->IQTARGET_PER = $cp_data['IQTARGET_PER'];
                $control_param->UNDELIVERED_CM_COLLECTION = $cp_data['UNDELIVERED_CM_COLLECTION'];
                $control_param->remarks_CM = $cp_data['remarks_CM'];
                $control_param->edit_payment_CM = $cp_data['edit_payment_CM'];
                $control_param->prev_pop = $cp_data['prev_pop'];
                $control_param->REF_SKU_DISPLAY = $cp_data['REF_SKU_DISPLAY'];
                $control_param->WH_DISTRIBUTOR = $cp_data['WH_DISTRIBUTOR'];
                $control_param->SPECIAL_DISCOUNT = $cp_data['SPECIAL_DISCOUNT'];
                $control_param->SUGGESTED_ORDER = $cp_data['SUGGESTED_ORDER'];
                $control_param->POP_TAG = $cp_data['POP_TAG'];
                $control_param->CM_ROUNDOFF = $cp_data['CM_ROUNDOFF'];
                $control_param->PARTIAL_UPLOAD = $cp_data['PARTIAL_UPLOAD'];
                $control_param->EXCESS_PAYMENT = $cp_data['EXCESS_PAYMENT'];
                $control_param->EXCESS_PAYMENT_MOD = $cp_data['EXCESS_PAYMENT_MOD'];
                $control_param->save();

            }
            //endregion

            //region pop
            if (isset($str['pop'])) {

                UltraPop::where('distributor', $distributor)->delete();

                foreach ($str['pop'] as $row) {
//                dump($row);

                    $pop = new UltraPop();
                    $pop->prev_town_code = $row['prev_town_code'];
                    $pop->SECTION = $row['SECTION'];
                    $pop->POP = $row['POP'];
                    $pop->TOWN = $row['TOWN'];
                    $pop->LOCALITY = $row['LOCALITY'];
                    $pop->SLOCALITY = $row['SLOCALITY'];
                    $pop->NAME = $row['NAME'];
                    $pop->POPTYPE = $row['POPTYPE'];
                    $pop->SUB_ELEMENT = $row['SUB_ELEMENT'];
                    $pop->COMPANY_RANK = $row['COMPANY_RANK'];
                    $pop->RANK = $row['RANK'];
                    $pop->AMOUNT_LIMIT = $row['AMOUNT_LIMIT'];
                    $pop->GST_PERCENTAGE = $row['GST_PERCENTAGE'];
                    $pop->VISIT_STATUS = $row['VISIT_STATUS'];
                    $pop->STATUS_TIME = $row['STATUS_TIME'];
                    $pop->LSM = $row['LSM'];
                    $pop->GEO_CODE = $row['GEO_CODE'];
                    $pop->DISTRICT = $row['DISTRICT'];
                    $pop->STRATA = $row['STRATA'];
                    $pop->PROVINCE = $row['PROVINCE'];
                    $pop->CREDIT_LIMIT = $row['CREDIT_LIMIT'];
                    $pop->CREDIT_ALLOWED = $row['CREDIT_ALLOWED'];
                    $pop->POPCODE = $row['POPCODE'];
                    $pop->DAYS_LIMIT = $row['DAYS_LIMIT'];
                    $pop->CREDITMODE = $row['CREDITMODE'];
                    $pop->CREDIT_ACTION = $row['CREDIT_ACTION'];
                    $pop->GST_REGISTERED = $row['GST_REGISTERED'];
                    $pop->POP_MODIFIED = $row['POP_MODIFIED'];
                    $pop->OUTSTANDING_CM = $row['OUTSTANDING_CM'];
                    $pop->VSTATUS = $row['VSTATUS'];
                    $pop->AREATYPE = $row['AREATYPE'];
                    $pop->MERCHANDIZING_ACTIVITY = $row['MERCHANDIZING_ACTIVITY'];
                    $pop->PROD_CHECK = $row['PROD_CHECK'];
                    $pop->GEO1 = $row['GEO1'];
                    $pop->TAX_EXCEPTION = $row['TAX_EXCEPTION'];
                    $pop->HOLDING_CAPACITY = $row['HOLDING_CAPACITY'];
                    $pop->SELLING_CAPACITY = $row['SELLING_CAPACITY'];
                    $pop->LONGITUDE = $row['LONGITUDE'];
                    $pop->LATITUDE = $row['LATITUDE'];
                    $pop->ASSET_SCHEME = $row['ASSET_SCHEME'];
                    $pop->GEO_BOUNDRY = $row['GEO_BOUNDRY'];
                    $pop->POP_IMAGE = $row['POP_IMAGE'];
                    $pop->GPS_COORDINATES = $row['GPS_COORDINATES'];
                    $pop->Master_Channel = $row['Master_Channel'];
                    $pop->Channel = $row['Channel'];
                    $pop->Sub_Channel = $row['Sub_Channel'];
                    $pop->Element = $row['Element'];
                    $pop->POPNO = $row['POPNO'];
                    $pop->prev_pop_code = $row['prev_pop_code'];
                    $pop->pjp = $row['pjp'];
                    $pop->distributor = $row['distributor'];
                    $pop->save();
                }
            }
            //endregion

            //region section
            if (isset($str['section'])) {

                Section::where('distributor', $distributor)->where('PJP', $PJPcode)->delete();
//                Section::where('distributor', $distributor)->delete();

                foreach ($str['section'] as $row) {
                    $section = new Section();
                    $section->code = $row['code'];
                    $section->sdesc = $row['sdesc'];
                    $section->working_date = $row['working_date'];
                    $section->delivery_date = $row['delivery_date'];
                    $section->ref_pjp = $row['ref_pjp'];
                    $section->ref_dsr = $row['ref_dsr'];
                    $section->ref_document = $row['ref_document'];
                    $section->ref_sub_document = $row['ref_sub_document'];
                    $section->ref_doc_no = $row['ref_doc_no'];
                    $section->Distributor = $row['Distributor'];
                    $section->PJP = $row['PJP'];
                    $section->save();
                }
            }
            //endregion

            //region locality
            if (isset($str['locality'])) {

                Locality::where('DISTRIBUTOR', $distributor)->delete();

                foreach ($str['locality'] as $row) {
                    $locality = new Locality();
                    $locality->COMPANY = $row['COMPANY'];
                    $locality->DISTRIBUTOR = $row['DISTRIBUTOR'];
                    $locality->TOWN = $row['TOWN'];
                    $locality->LOCALITY = $row['LOCALITY'];
                    $locality->SDESC = $row['SDESC'];
                    $locality->LDESC = $row['LDESC'];
                    $locality->save();
                }

            }
            //endregion

            //region sub_locality
            if (isset($str['sub_locality'])) {

                SubLocality::where('DISTRIBUTOR', $distributor)->delete();

                foreach ($str['sub_locality'] as $row) {
                    $sub_locality = new SubLocality();
                    $sub_locality->COMPANY = $row['COMPANY'];
                    $sub_locality->DISTRIBUTOR = $row['DISTRIBUTOR'];
                    $sub_locality->TOWN = $row['TOWN'];
                    $sub_locality->LOCALITY = $row['LOCALITY'];
                    $sub_locality->SLOCALITY = $row['SLOCALITY'];
                    $sub_locality->LSM = $row['LSM'];
                    $sub_locality->SDESC = $row['SDESC'];
                    $sub_locality->LDESC = $row['LDESC'];
                    $sub_locality->save();
                }

            }
            //endregion

            Log::notice('LOG-END: ' . __FUNCTION__);
            return $this->getData($distributor, $DSRcode, $PJPcode);

        } else {
            Log::notice('LOG-END: ' . __FUNCTION__);
            return response()->json([
                'success' => 0,
                'message' => $message
            ]);
        }

    }

    /**
     * @param $distributor
     * @param $DSRcode
     * @param $PJPcode
     * @return \Illuminate\Http\JsonResponse
     *
     * Get Data
     */
    public function getData($distributor, $DSRcode, $PJPcode)
    {
        /**
         * TODO:: show only those pops which has not been surveyed yet.
         * Check this logic
         */
        //region POP-DATA Version 2 (Working at least) Revert on it if something failed
        /*/
        $pop_codes_will_skipped = Pop::select('pop_code')
            ->where('pop_closed_temporarily', 0)
            //->orWhere('verification_status_tm', 1)
            ->groupBy('pop_code')
            ->pluck('pop_code')->toArray();

        $pop_data = [];
        $pops = UltraPop::where('distributor', $distributor)->get();
        foreach ($pops as $pop) {
            if (!in_array($pop->POPCODE, $pop_codes_will_skipped)) {
                $pop_data[] = $pop;
            }
        }
        //*/
        //endregion

        //region Version 4 (Test it) SubQueryLogic
        /*//
        $pop_codes_will_skipped = DB::table('pops AS p')->select('p.pop_code')->where(function ($q) {

            $q->where(function ($query) {
                $query->where('p.pop_closed_permanently', 0);
                $query->where('p.pop_closed_temporarily', 1);
                $query->where('p.verification_status_tm', 0);
                $query->orWhere('p.verification_status_tm', 2);
                $query->orWhere('p.verification_status_tm', 3);
                $query->orWhere('p.verification_status_tm', 4);
            })->orWhere(function ($query) {
                $query->where('p.pop_closed_permanently', 1);
                $query->where('p.pop_closed_temporarily', 0);
                $query->where('p.verification_status_tm', 0);
                $query->orWhere('p.verification_status_tm', 2);
                $query->orWhere('p.verification_status_tm', 3);
                $query->orWhere('p.verification_status_tm', 4);
            })->orWhere(function ($query) {
                $query->where('p.pop_closed_temporarily', 0);
                $query->where('p.pop_closed_permanently', 0);
                $query->where('p.verification_status_tm', 0);
                $query->orWhere('p.verification_status_tm', 2);
                $query->orWhere('p.verification_status_tm', 3);
                $query->orWhere('p.verification_status_tm', 4);
            });

        })->groupBy('p.pop_code');

//    dump($pop_codes_will_skipped->toSql());
//    dump($pop_codes_will_skipped->getBindings());

        $output = \DB::table('ultra_pop AS up')
            ->where('up.distributor', $distributor)
            ->whereNotIn('up.POPCODE', $pop_codes_will_skipped);

//        $output = DB::table('ultra_pop AS up')->where(function ($up_query) use ($distributor, $pop_codes_will_skipped) {
//            $up_query->where(function ($up_q) use ($distributor, $pop_codes_will_skipped) {
//                $up_q->where('up.distributor', $distributor);
//            })->orWhere(function ($up_q) use ($distributor, $pop_codes_will_skipped) {
//                $up_q->where('up.distributor', $distributor)
//                    ->WhereIn('up.POPCODE', $pop_codes_will_skipped);
//            });
//        });

        Log::warning($output->toSql());
        Log::warning($output->getBindings());

        //dump($output->count());
        $pop_data = [];
        foreach ($output->get() as $row) {
            //dump($row->POPCODE);
            $pop_data[] = $row;
        }
        //dump($pop_data);
        //*/
        //endregion

        $pop_codes_will_skipped = DB::table('pops AS p')->select('p.pop_code')->where(function ($q) {
            $q->where('p.pop_closed_temporarily', 0)->where('p.verification_status_tm', '<>', 2);
        })->groupBy('p.pop_code');

        $output = \DB::table('ultra_pop AS up')
            ->where('up.distributor', $distributor)
            ->whereNotIn('up.POPCODE', $pop_codes_will_skipped);

        $pop_data = [];
        foreach ($output->get() as $row) {
            $pop_data[] = $row;
        }

        $territory_manager = \DB::table('distributor_user AS du')
            ->join('users AS u', 'u.id', '=', 'du.user_id')
            ->where('du.distributor_distributor', $distributor)
            ->select('u.id AS TM', 'u.name AS SDESC')
            ->get();

        $localities = Locality::getDsrLocalityList($distributor);
        //$sub_localities = SubLocality::where('distributor', $distributor)->get();
        $sub_localities = SubLocality::getDsrSubLocalityList($distributor);

        return response()->json([
            'success'           => 1,
            'control_param'     => ControlParam::where('distributor', $distributor)->where('DSRcode', $DSRcode)->where('PJPcode', $PJPcode)->get(),
            'section'           => Section::where('distributor', $distributor)->where('PJP', $PJPcode)->get(),
            'pop'               => $pop_data,
            'locality'          => $localities,
            'sub_locality'      => $sub_localities,
            'channel'           => Channel::getChannels(),
            'area_type'         => AreaType::getAreaTypes(),
            'category'          => Category::getCategories(),
            'territory_manager' => $territory_manager,
            'version'           => '1.0.2',
            'show_ads'          => false
        ]);
    }
}
