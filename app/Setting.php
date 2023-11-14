<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Main
{
    use SoftDeletes;
    protected $primaryKey = 'keys';

    public static function getAll($key = null)
    {
        static $settings;
        if (!$settings) {

            if (!\Schema::hasTable('settings')) {
                //throw new \Exception("settings table not found.");
                return false;
            }

            $collection = self::all(['key', 'value'])->toArray();
            if ($collection) {
                foreach ($collection as $item) {
                    $settings[ $item[ 'key' ] ] = $item[ 'value' ];
                }
            }
        }
        if (!is_null($key) && !is_null($settings)) {
            return array_key_exists($key, $settings) ? $settings[ $key ] : false;
        }
        return $settings;
    }

    public static function getBaseUrl($https = false)
    {
        return $https ? self::getHttpsUrl() : self::getHttpUrl();
    }

    public static function getHttpUrl()
    {
        return self::getAll('http_url');
    }

    public static function getHttpsUrl()
    {
        return self::getAll('https_url');
    }

    public static function getFrontendTheme()
    {
        return self::getAll('theme.frontend');
    }

    public static function getAdminTheme()
    {
        return self::getAll('theme.backend.admin');
    }

    public static function getVersion()
    {
        return self::getAll('version');
    }

    public static function getOwnerEmail()
    {
        return self::getAll('email.owner');
    }

    public static function getInfoEmail()
    {
        return self::getAll('email.info');
    }

    public static function getSupportEmail()
    {
        return self::getAll('email.support');
    }

    public static function getNoReplyEmail()
    {
        return self::getAll('email.no_reply');
    }

    public static function getVendorName()
    {
        return self::getAll('email.vendor_name');
    }

    public static function getVendorUrl()
    {
        return self::getAll('email.vendor_url');
    }

    public static function getWebMasterEmail()
    {
        return self::getAll('email.web_master');
    }

    public static function getProjectManagerEmail()
    {
        return self::getAll('email.project_manager');
    }

    public static function getCurrency()
    {
        return self::getAll('currency');
    }

    public static function getStandardDateFormat()
    {
        return self::getAll('date.format');
    }

    public static function getStandardDateTimeFormat()
    {
        return self::getAll('datetime.format');
    }

    public static function getMaintenanceMode()
    {
        return self::getAll('maintenance.mode');
    }
}
