<?php namespace App\Http\Controllers\Backend;

use Auth;
use App\Module;
use App\User;
use Illuminate\Http\Request;
use Mail;
use DB;
use PermissionHelper;
use App\Http\Requests\Backend\AccountInfoUpdateRequest;
use App\Http\Requests\Backend\AccountPasswordUpdateRequest;

class BackendAccountController extends BackendController
{
    private $module;

    public function __construct()
    {
        parent::__construct();
        $this->module = Module::where('url', $this->getModuleUrl())->first();
    }

    public function getProfile(User $user)
    {
        $pageMode = 'My Profile';
        $module = $this->module;
        $user = User::find(Auth::user()->id);

        return view(admin_view('accounts.profile'), compact('module', 'pageMode', 'user'));
    }

    public function putInfoUpdate(AccountInfoUpdateRequest $request)
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);
        $user->name = $request->name;
        //$user->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
        $user->save();

        $request->session()->flash('alert-success', 'Info has been updated successfully.');

        return redirect(route('admin-profile'));
    }

    public function putPasswordUpdate(AccountPasswordUpdateRequest $request)
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);
        $user->password = bcrypt($request->password);
        $user->save();

        $request->session()->flash('alert-success', 'Password has been updated successfully.');
        return response()->json(['success' => true, 'msg' => 'Password has been updated successfully.']);
        //return redirect(route('admin-profile'));
    }

    /**
     * Update User Avatar
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAvatar(Request $request)
    {
        //TODO:: do some validations for image.

//        dump($request->all());
//        dump($request->file('avatar'));
//        if ($request->hasFile('avatar')) {
//            $image = $request->file('avatar');
//            $filename = time() . '.' . $image->getClientOriginalExtension();
//            $x = $request->file('avatar')->move(cms_upload_path('avatars'), $filename);
//            dump($x);
//        }


        $user = Auth::user();
        $data = $request->avatar;

//        dump($data);
//        dd(is_base64_image($data));

        list($type, $data) = explode(';', $data);
        list(, $data) = explode(',', $data);

        $data = base64_decode($data);

        $image_name = 'avatar_'. $user->id . '.png';
        $path = cms_upload_path('avatars/' . $image_name);

        if (file_put_contents($path, $data)) {

            // Insert to DB
            DB::table('users')
                ->where('id', $user->id)
                ->update(['avatar' => $image_name]);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Avatar has been uploaded successfully'
                ]
            );
        }

        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong'
        ], 422);
    }
}
