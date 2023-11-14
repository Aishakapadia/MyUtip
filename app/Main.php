<?php

namespace App;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Main extends BaseModel
{
    const ACTIVE = 1;
    protected static $locale_column;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $lang = \App::getLocale();
        self::$locale_column = $lang != 'en' ? '_arabic' : '';
    }

    public static function kUpdate($model, $where, $data)
    {
        return $model::where($where)->update($data);
    }

    public static function getDropDown()
    {
        return self::where('active', 1)
            ->whereNull('deleted_at')
            ->orderBy('title', 'ASC')
            ->pluck('title', 'id');
    }
}
