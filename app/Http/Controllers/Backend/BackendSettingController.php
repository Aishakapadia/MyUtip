<?php namespace App\Http\Controllers\Backend;

use DB;
use View;
use App\Country;
use App\Setting;
use App\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class BackendSettingController extends BackendController
{
    private $module;

    public function __construct()
    {
        parent::__construct();
        $this->module = Module::where('url', $this->getModuleUrl())->first();
    }

    public function getManage()
    {
        $module = $this->module;
        $setting_data = Setting::all();
        // foreach($settings as $key => $value) {
        //     dump($value->description);
        // }
        // dd($settings);

        // Show the page
        return view(admin_view('settings.manage'), compact('setting_data', 'module'));
    }

    /**
     * Update Settings
     */
    public function postUpdate(Request $request)
    {
        if (DB::table('settings')->where('id', $request->pk)->update(array('value' => $request->value))) {
            return response()->json([
                'success' => true,
                'message' => 'Updated.',
            ], 200);
        }
    }

    public function getDefaultCurrency()
    {
        $pageMode      = 'Edit';
        $record        = Setting::where('key', 'currency')->get(['key', 'value'])->first();
        $currency_list = Country::lists('currency_code', 'currency_code');

        return view(admin_view('settings.default_currency'), compact('pageMode', 'currency_list', 'record'));
    }

    public function putUpdateDefaultCurrency(DefaultCurrencyRequest $request)
    {
        $record        = Setting::where('key', 'currency')->first();
        $record->value = Input::get('currency');
        $record->save();

        $request->session()->flash('alert-success', 'Record has been updated successfully.');

        return redirect(admin_url('setting/default-currency'));
    }


}
