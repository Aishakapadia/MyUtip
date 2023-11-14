<?php

use App\User;
use App\Role;

class PermissionHelper
{

    protected static $except_urls = [
        'login',
        'logout',
        'account/profile',
        'account/avatar',
        'material/detail',
        'ticket/status',
    ];

    public static function check($request)
    {
        if ($request->user() === null) {
            //return response("Insufficient Permissions", 401);
            return redirect(admin_url('login'));
        }

        // TODO:: enhance it if needed.
        $current_path = substr($request->path(), 6); // removing "panel/" from uri
        $x = explode('/', $current_path);
        if (count($x) > 2) {
            $match_path = $x[0] . '/' . $x[1];
        } else {
            $match_path = $current_path;
        }

        $allowed_urls = self::$except_urls;
        $role_id = $request->user()->role->id;
        $role = Role::find($role_id);
        $modules = $role->modules;
        if ($modules->count()) {
            foreach ($modules as $module) {
                $allowed_urls[] = $module->url;
            }
        }

        if (in_array($match_path, $allowed_urls)) {
            return true;
        }

    }

    /**
     * Validate using URL
     *
     * @param $url
     * @param null $user_id
     * @return bool
     */
    public static function isAllowed($url, $user_id = null)
    {
        if (\Auth::user()) {
            $user_id = $user_id ? $user_id : \Auth::user()->id;
            $user = User::find($user_id);
            if ($user !== null) {

                $allowed_urls = array();
                $role_id = $user->role->id;
                $role = Role::find($role_id);
                $modules = $role->modules;
                if ($modules->count()) {
                    foreach ($modules as $module) {
                        $allowed_urls[] = $module->url;
                    }
                }

                if (in_array($url, $allowed_urls)) {
                    return true;
                }
            }
        }
    }

    /**
     * Validate using module_id
     *
     * @param $id
     * @param null $user_id
     * @return bool
     */
    public static function isAllowedModuleId($id, $user_id = null)
    {
        if (\Auth::user()) {
            $user_id = $user_id ? $user_id : \Auth::user()->id;
            $user = User::find($user_id);
            if ($user !== null) {

                $allowed_module_ids = array();
                $role_id = $user->role->id;
                $role = Role::find($role_id);
                $modules = $role->modules;
                if ($modules->count()) {
                    foreach ($modules as $module) {
                        $allowed_module_ids[] = $module->id;
                    }
                }

                if (in_array($id, $allowed_module_ids)) {
                    return true;
                }
            }
        }
    }

    public static function isTransporter($user_id = null)
    {
        if (\Auth::user()) {
            $user_id = $user_id ? $user_id : \Auth::user()->id;
            $user = User::find($user_id);
            if ($user->isTransporter()) {
                return true;
            }
        }
    }

    public static function isSupplier($user_id = null)
    {
        if (\Auth::user()) {
            $user_id = $user_id ? $user_id : \Auth::user()->id;
            $user = User::find($user_id);
            if ($user->isSupplier()) {
                return true;
            }
        }
    }

    public static function isAdmin($user_id = null)
    {
        if (\Auth::user()) {
            $user_id = $user_id ? $user_id : \Auth::user()->id;
            $user = User::find($user_id);
            if ($user->isAdmin()) {
                return true;
            }
        }
    }

}