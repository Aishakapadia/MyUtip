<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Main
{
    /**
     * Get Menu's children list
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menu_list()
    {
        return $this->hasMany('App\MenuList');
    }

    /**
     * Get all menus
     *
     * @return mixed
     */
    public static function getAll()
    {
        return self::where('active', self::ACTIVE)->get();
    }

    public static function getWhere(array $where)
    {
        return self::where('active', self::ACTIVE)
            ->where($where)
            ->get();
    }
}