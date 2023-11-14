<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuList extends Model
{
    public function locations()
    {
        return $this->belongsToMany('App\MenuLocation', 'menu_location_list', 'menu_location_id', 'menu_list_id');
    }

    public static function getListByParentId($parent_id)
    {
        return MenuList::where('active', 1)
            ->where('parent', $parent_id)
            ->orderBy('sort')
            ->get();
    }

    public static function getLinks()
    {
        return MenuList::where('page_slug', '=', '')
            ->orWhereNull('page_slug')
            ->orderBy('sort')
            ->get();
    }

    public static function getLists()
    {
        return MenuList::where('active', 1)
            ->where('ready', 1)
            ->orderBy('sort')
            ->get();
    }
}
