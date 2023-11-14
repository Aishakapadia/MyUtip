<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuLocation extends Model
{
    public function lists()
    {
        return $this->belongsToMany('App\MenuList', 'menu_location_list', 'menu_location_id', 'menu_list_id')
            ->orderBy('sort');
    }

    public function getLists($parent)
    {
        return $this->lists()
            ->where('parent', $parent);
    }
}
