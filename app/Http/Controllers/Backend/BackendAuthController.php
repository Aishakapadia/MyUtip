<?php

namespace App\Http\Controllers\Backend;

use Auth;
use App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

//use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
//use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class BackendAuthController extends Controller
{

//    use AuthenticatesAndRegistersUsers;
    use AuthenticatesUsers;

    protected $redirectTo = '/panel/dashboard';

    public function getLogin()
    {
        if (Auth::user()) {
            return redirect(route('dashboard'));
        }
        return view(admin_view('login'));
    }

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function postLogin(Request $request)
    {
        //dd($request->all());
        $output = [];
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->input('remember');

//        if ($email != 'zarpio@gmail.com') {
//            $email = trim($email) . '@unilever.com';
//        }

        /**
         * If ajax request
         */
        if ($request->ajax()) {

            $output = ['success' => false, 'msg' => 'These credentials do not match our records.'];

            if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
                // Authentication passed...
                $output = ['success' => true, 'msg' => 'You may login.', 'redirect_url' => admin_url('dashboard')];
            }

            return response()->json($output);
        }

        $user = \App\User::where('email', $email)->first();
        if ($user) {
            if (!$user->active) {
                $this->incrementLoginAttempts($request);
                return redirect()
                    ->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors(['active' => 'You must be active to login.']);
            }

            if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
                // Authentication passed...
                return redirect()->intended(admin_url('dashboard'));
            }
        }

        return redirect(admin_url('/login'))
            //->withInput( Request::only( 'email', 'remember' ) )
            ->withErrors(
                [
                    'email' => 'These credentials do not match our records.'
                ]
            );
    }

    public function getLogout()
    {
        Auth::logout();
        \Session::flash('message', 'Logout successfully.');
        return redirect(admin_url('login'));
    }
}