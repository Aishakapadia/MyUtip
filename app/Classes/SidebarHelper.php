<?php

use App\Http\Requests;

class SidebarHelper
{
    const SB_DASHBOARD = 'dashboard';
    const SB_ATTRIBUTE = 'attribute';
    const SB_BRAND = 'brand';
    const SB_SLIDER = 'slider';
    const SB_CATEGORY = 'category';
    const SB_COUNTRY = 'country';
    const SB_PRODUCT = 'product';
    const SB_USER = 'user';
    const SB_PAGE = 'page';
    const SB_NAVIGATION = 'navigation';

    const SB_HOME = 'home';
    const SB_LOCATION = 'location';
    const SB_SETTING = 'setting';

    private $sidebar = array();

    public static function instance()
    {
        static $instance;
        if (!$instance) {
            $instance = new SidebarHelper();
        }
        return $instance;
    }

    public function __construct()
    {

        $this->sidebar = [

            self::SB_DASHBOARD => [
                'panel/dashboard'
            ],

            self::SB_NAVIGATION => [
                'panel/navigation/settings'
            ],

            self::SB_PAGE => [
                'panel/page/manage',
                'panel/page/settings',
                'panel/page/create'
            ],

            self::SB_USER => [
                'panel/user/manage',
                'panel/user/create',
                'panel/user/edit',
            ],

            self::SB_CATEGORY => [
                'panel/category/manage',
                'panel/category/create',
                'panel/category/edit',
            ],

            self::SB_COUNTRY => [
                'panel/country/manage',
                'panel/country/create',
                'panel/country/edit',
            ],


            self::SB_LOCATION => [
                'panel/city/manage',
                'panel/area/manage',
                'panel/store/manage',
            ],

            self::SB_SETTING => [
                'panel/setting/manage',
            ]
        ];
    }

    public function checkSidebarGroup($group)
    {
        if (!array_key_exists($group, $this->sidebar)) {
            return false;
        }

        $groupUrls = $this->sidebar[$group];
        $current = Request::path();
        foreach ($groupUrls as $url) {
//            if ( preg_match( '@' . preg_quote( $url ) . '$@i', $current ) ) {
            if (preg_match('@' . preg_quote($url) . '(/[0-9]+)?$@i', $current)) {
                return true;
            }
        }

        return false;
    }

    public function checkSidebarUrl($url)
    {
        $current = Request::path();
        if (preg_match('@' . preg_quote($url) . '$@i', $current)) {
            return true;
        }
        return false;
    }

}