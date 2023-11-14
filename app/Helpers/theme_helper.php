<?php

use \App\Setting;

/*
 * Frontend Section
 */
function locale($col)
{
    $lang = \App::getLocale();
    $locale_column = $lang != 'en' ? '_arabic' : '';
    return $col . $locale_column;
}

function frontend_view($view)
{
    return 'frontend.' . Setting::getFrontendTheme() . '.' . $view;
}

function frontend_layout($view)
{
    return 'frontend.' . Setting::getFrontendTheme() . '.layouts.' . $view;
}

function frontend_asset($path)
{
    return url('/frontend/' . Setting::getFrontendTheme() . '/' . ltrim($path, '/'));
}

function frontend_url($path)
{
    return url($path);
}

function newsletter_asset($path)
{
    return url('/newsletter/' . ltrim($path, '/'));
}

function frontend_setting($key = null)
{
    static $settings;
    if (!$settings) {
        $viewsPath = Config::get('view.paths')[0];
        $paths[] = $viewsPath;
        $paths[] = 'frontend';
        $paths[] = Setting::getFrontendTheme();
        $paths[] = 'settings.php';
        $settingsFile = implode(DIRECTORY_SEPARATOR, $paths);
        if (!file_exists($settings)) {
            return null;
        }
        include_once $settingsFile;
        if (!$settings || !is_array($settings)) {
            return null;
        }
        $settings = array_dot($settings);
    }

    if (!is_null($key)) {
        return array_key_exists($key, $settings) ? $settings[$key] : null;
    }
    return $settings;
}

/*
 * Admin Section
 */

function admin_view($view)
{
    return sprintf("backend.%s.%s", Setting::getAdminTheme(), $view);
}

function admin_layout($view)
{
    return sprintf("backend.%s.layouts.%s", Setting::getAdminTheme(), $view);
}

function admin_asset($path)
{
    return url(sprintf("/backend/%s/%s", Setting::getAdminTheme(), ltrim($path, '/')));
}

function admin_url($path)
{
    return url('/panel/' . ltrim($path, '/'));
}

function admin_setting($key = null)
{
    static $settings;
    if (!$settings) {
        $viewsPath = Config::get('view.paths')[0];
        $paths[] = $viewsPath;
        $paths[] = 'backend';
        $paths[] = 'admin';
        $paths[] = Setting::getAdminTheme();
        $paths[] = 'settings.php';
        $settingsFile = implode(DIRECTORY_SEPARATOR, $paths);
        if (!file_exists($settingsFile)) {
            return null;
        }
        include_once $settingsFile;
        if (!$settings || !is_array($settings)) {
            return null;
        }
        $settings = array_dot($settings);
    }
    if (!is_null($key)) {
        return array_key_exists($key, $settings) ? $settings[$key] : null;
    }
    return $settings;
}

function anchor_delete($url, $title = 'Delete')
{
    $return = '';
    $return .= '<a href="' . $url . '" onclick="return confirm(\'Are you sure you want to delete this record?\');"><i class="fa fa-trash-o"></i>' . $title . '</a>';

    return $return;
}

function user_photos_path()
{
    return public_path() . '/frontend/' . Setting::getFrontendTheme() . '/' . '/profile_images/' . Auth::user()->id . '/';
}

function uploadFileThumbnail($file, $width, $height)
{

    if (!empty($file)) {
        $destinationPath = public_path() . '/upload/';

        $file = str_replace('data:image/png;base64,', '', $file);
        $img = str_replace(' ', '+', $file);
        $data = base64_decode($img);
        $filename = date('ymdhis') . '_croppedImage' . ".png";
        $file = $destinationPath . $filename;
        $success = file_put_contents($file, $data);

        // THEN RESIZE IT
        $returnData = 'upload/' . $filename;
        $image = Image::make(file_get_contents(URL::asset($returnData)));
        $image = $image->resize($width, $height)->save($destinationPath . $filename);

        if ($success) {
            return $returnData;
        }
    }
}

function cms_upload_path($path = null)
{
    return $path ? public_path('uploads' . DIRECTORY_SEPARATOR . $path) : public_path('uploads' . DIRECTORY_SEPARATOR);
}

function cms_upload_url($filename = null)
{
    return $filename ? url('uploads/' . $filename) : url('uploads/');
}

function path_brand_icon()
{
    return public_path('uploads' . DIRECTORY_SEPARATOR . 'brands' . DIRECTORY_SEPARATOR);
}

function img_brand_icon($filename)
{
    $src = url('uploads/brands/' . $filename);
    if (file_exists($src)) {
        return '<img src="' . $src . '" alt="img" width="60" height="60">';
    }
    return '<img src="' . url('uploads/default-60x60.png') . '" alt="img" width="60" height="60">';
}

function img_slider_icon($filename)
{
    $src = url('uploads/slides/' . $filename);
    return '<img src="' . $src . '" alt="img" width="60" height="60">';
}

function img_category_icon($filename)
{
    $src = url('uploads/categories/' . $filename);
    if (file_exists($src)) {
        return '<img src="' . $src . '" alt="img" width="60" height="60">';
    }
    return '<img src="' . url('uploads/default-60x60.png') . '" alt="img" width="60" height="60">';
}

function img_product_icon($filename)
{
    $src = url('uploads/products/' . $filename);
    return '<img src="' . $src . '" alt="img" width="60" height="60">';
}

function get_product_image($product_id)
{
    $output = 'default.jpg';

    $product = Product::find($product_id);
    //dump($product);
    if ($product->image_main->count()) {
        $output = $product->image_main->first()->file;
    }

    return $output;
}

function friendly_url($type, $title, $id = null)
{
    /**
     * type = category
     * type = product
     * type = brand
     */
    if ($id) {
        return url($type . '/' . str_slug($title) . '-' . $id);
    }
    return url($type . '/' . str_slug($title));
}

function formatAmount($value, $decimal = 2, $symbol = true)
{
    if ($symbol) {
        if (ENV('CURRENCY_DIRECTION') == 'LEFT') {
            return ENV('CURRENCY_SYMBOL', 'Rs.') . ' ' . number_format($value, $decimal, '.', ',');
        } else {
            return number_format($value, $decimal, '.', ',') . ' ' . ENV('CURRENCY_SYMBOL', 'PKR');
        }
    }

    return number_format($value, $decimal, '.', ',');

//    return $symbol ? number_format($value, $decimal, '.', ',') . ' PKR' : number_format($value, $decimal, '.', ',');
}

function limit_words($string, $word_limit)
{
    $words = explode(" ", $string);
    if (count($words) > $word_limit) {
        return implode(" ", array_splice($words, 0, $word_limit)) . ' ...';
    }
    return implode(" ", array_splice($words, 0, $word_limit));
}

function generateMovableList($menu)
{
    $output = '';

    if ($menu->lists) {
        $output .= '<ol class="dd-list">';

        if ($menu->getLists(0)->get()) {
            foreach ($menu->getLists(0)->get() as $list) {
                $output .= '<li class="dd-item dd3-item" data-id="' . $list->id . '" data-title="' . $list->title . '">';
                $output .= '<div class="dd-handle dd3-handle"> </div>';
                $output .= '<div class="dd3-content">';
                $output .= $list->title;
                $output .= '<span style="float:right;"><a class="remove_location_list" data-menu-list-id="' . $list->id . '" title="Remove from navigation"><i class="fa fa-trash" aria-hidden="true"></i>Remove</a></span>';
                $output .= '</div>';

                if ($menu->getLists($list->id)->count() > 0) {
                    $output .= '<ol class="dd-list">';
                    foreach ($menu->getLists($list->id)->get() as $child_first) {
                        $output .= '<li class="dd-item dd3-item" data-id="' . $child_first->id . '" data-title="' . $child_first->title . '">';
                        $output .= '<div class="dd-handle dd3-handle"> </div>';
                        $output .= '<div class="dd3-content">';
                        $output .= $child_first->title;
                        $output .= '<span style="float:right;"><a class="remove_location_list" data-menu-list-id="' . $child_first->id . '" title="Remove from navigation"><i class="fa fa-trash" aria-hidden="true"></i>Remove</a></span>';
                        $output .= '</div>';

                        if ($menu->getLists($child_first->id)->count() > 0) {
                            $output .= '<ol class="dd-list">';
                            foreach ($menu->getLists($child_first->id)->get() as $child_second) {
                                $output .= '<li class="dd-item dd3-item" data-id="' . $child_second->id . '" data-title="' . $child_second->title . '">';
                                $output .= '<div class="dd-handle dd3-handle"> </div>';
                                $output .= '<div class="dd3-content">';
                                $output .= $child_second->title;
                                $output .= '<span style="float:right;"><a class="remove_location_list" data-menu-list-id="' . $child_second->id . '" title="Remove from navigation"><i class="fa fa-trash" aria-hidden="true"></i>Remove</a></span>';
                                $output .= '</div>';

                            }
                            $output .= '</ol>';
                        }

                    }
                    $output .= '</ol>';
                }

                $output .= '</li>';
            }
        }

        $output .= '</ol>';
    }

    return $output;
}

function nav_page_or_url($slug, $url)
{
    if ($slug != '' || $url != '') {
        return ($slug != '') ? $slug : $url;
    }

    return '#';
}

function getStatusSelect()
{
    return Form::select('active', [1 => 'Active', 0 => 'Inactive'], null, ['class' => 'form-control']);
}

/**
 * Generate Parent Checkboxes for Permission Module under Roles.
 *
 * @param  string $class class-name
 * @param  string $label label-name
 * @return html        parent-checkboxes
 */
function module_parent_checkbox($class, $label)
{
    $output = '';

    if ($class && $label) {
        $output .= '<div class="md-checkbox has-error">';
        $output .= '<input type="checkbox" id="' . $class . '" class="md-check ' . $class . '">';
        $output .= '<label for="' . $class . '">';
        $output .= '<span></span>';
        $output .= '<span class="check"></span>';
        $output .= '<span class="box"></span>' . $label;
        $output .= '</label>';
        $output .= '</div">';
    }

    return $output;
}

/**
 * Generate Children Checkboxes for Permission Module under Roles.
 *
 * @param  collection $row data
 * @param  array $selected_permissions permissions
 * @param  collection $module module data
 * @param  string $class class-name
 * @return html                       parent-checkboxes
 */
function module_child_checkbox($row, $selected_permissions, $module, $class)
{
    $output = '';

    if ($row && $selected_permissions && $module && $class) {
        $output .= '<div class="md-checkbox">';
        $output .= Form::checkbox('permissions[' . $row->id . ']', $row->id, in_array($row->id, $selected_permissions) ? true : null, ['class' => $class . $row->parent, 'id' => $row->id]);
        $output .= '<label for="' . $row->id . '">';
        $output .= '<span></span>';
        $output .= '<span class="check"></span>';
        $output .= '<span class="box"></span>';
        $output .= '</label>';
        $output .= '</div">';
    }

    return $output;
}


function myCheckbox($id, $class = null, $label = null, $parent = false, $attributes = [])
{
    $output = '';

    if ($id) {
        if ($parent) {
            $output .= '<div class="md-checkbox has-error">';
        } else {
            $output .= '<div class="md-checkbox">';
        }

        $output .= '<input type="checkbox" id="' . $id . '" class="md-check ' . $class . '"';

        if ($attributes) {
            foreach ($attributes as $key => $value) {
                $output .= ' ' . $key . '="' . $value . '" ';
            }
        }

        $output .= '>';

        $output .= '<label for="' . $id . '">';
        $output .= '<span></span>';
        $output .= '<span class="check"></span>';
        $output .= '<span class="box"></span>' . $label;
        $output .= '</label>';
        $output .= '</div">';
    }

    return $output;
}

function avatar($user = null)
{
    if (\Auth::user()) {
        $user = \App\User::find(\Auth::user()->id);
    }

    if ($user && $user->avatar) {
        return url('uploads/avatars/' . $user->avatar) . '?' . time();
    }

    return admin_asset('assets/pages/media/profile/profile_user.jpg');
}