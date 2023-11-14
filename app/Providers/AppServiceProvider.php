<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Setting;
use App\ModuleType;
use App\Module;
use App\MenuList;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Schema::defaultStringLength(191);

        //region For every views
        /**
         * For every views
         */
        view()->composer('*', function ($view) {
            $settings = Setting::getAll();
            //dump($settings);

            $view
                ->with('settings', $settings);
            //->with('wishlist_total_qty', $wishlist_total_qty);
        });
        //endregion

        //region Backend Sidebar
        /**
         * Sidebar Menu
         */
        view()->composer(

            admin_view('partials.sidebar'), function ($view) {

            $module_types = ModuleType::select(['id', 'title', 'description'])
                ->where('active', 1)
                ->whereNull('deleted_at')
                ->orderBy('sort', 'ASC')
                ->get();

            $sidebar_menu = array();
            if ($module_types) {
                foreach ($module_types as $module_type_key => $module_type) {

                    $modules = Module::select(['id', 'title', 'description', 'url', 'icon', 'parent', 'visible_in_sidebar'])
                        ->where('module_type_id', $module_type->id)
                        ->where('active', 1)
                        ->where('parent', 0)
                        ->whereNull('deleted_at')
                        ->orderBy('sort', 'ASC')
                        ->get();

                    $module_titles = array();
                    if ($modules) {
                        foreach ($modules as $module) {

                            $sub_modules = Module::select(['id', 'title', 'description', 'url', 'icon', 'parent', 'visible_in_sidebar'])
                                ->where('module_type_id', $module_type->id)
                                ->where('active', 1)
                                ->where('parent', $module->id)
                                //->where('visible_in_sidebar', 1)
                                ->whereNull('deleted_at')
                                ->orderBy('sort', 'ASC')
                                ->get();

                            $module_lists = array();
                            if ($sub_modules) {
                                foreach ($sub_modules as $sub_module) {
                                    $module_lists[] = array(
                                        'sub_id'             => $sub_module->id,
                                        'sub_title'          => $sub_module->title,
                                        'sub_url'            => $sub_module->url,
                                        'visible_in_sidebar' => $sub_module->visible_in_sidebar
                                    );
                                }
                            }

                            $module_titles[] = array(
                                'main_id'            => $module->id,
                                'main_title'         => $module->title,
                                'main_url'           => $module->url,
                                'main_icon'          => $module->icon,
                                'visible_in_sidebar' => $module->visible_in_sidebar,
                                'sub_sub'            => $module_lists
                            );
                        }
                    }

                    $sidebar_menu[] = array(
                        'module_type_id'    => $module_type->id,
                        'module_type_title' => $module_type->title,
                        'sub'               => $module_titles
                    );

                }
            }

//            dump($sidebar_menu);

            $view->with('sidebar_menu', $sidebar_menu);

        });
        //endregion
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
