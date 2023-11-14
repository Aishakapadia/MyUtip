<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Main
{
    //region Relations
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'permissions', 'module_id', 'role_id');
    }

    public function current_user_role()
    {
        if (\Auth::user()) {
            return $this->belongsToMany('App\Role', 'permissions', 'module_id', 'role_id')
                ->where('role_id', \Auth::user()->id);
        }
        return false;
    }

    public function father()
    {
        return $this->hasOne('App\Module', 'id', 'parent');
    }

    public function babies()
    {
        return $this->hasMany('App\Module', 'parent', 'id');
    }
    //endregion
}
