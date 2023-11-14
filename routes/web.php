<?php

use Carbon\Carbon;

//region Test/Debug Routes
/**
 * for logs visit
 * http://domain/log-viewer/logs
 */


Route::get('/start', 'ParserController@start');
Route::get('/combinations', 'ParserController@generateCombinations');
Route::get('/export-parsed-data', 'ParserController@getDoExport');

Route::get('/test', function () {
    $name = 'khalil';
    dd('ok');
});
//endregion

Route::get('/log-execute', function () {
    $destinationPath = public_path('/uploads/logs/');
    $myFile = $destinationPath . "log.txt";
    $file = file_get_contents($myFile);

    $pattern = '
        /
        \{              # { character
            (?:         # non-capturing group
                [^{}]   # anything that is not a { or }
                |       # OR
                (?R)    # recurses the entire pattern
            )*          # previous group zero or more times
        \}              # } character
        /x
    ';
    preg_match_all($pattern, $file, $matches);
    $match = $matches[0];
    //dd($match);
    if ($match) {

        //*//
        $client = new GuzzleHttp\Client();
        $res = $client->request('POST', 'local.mysite.com/api/survey', [
            'form_params' => [
                'data' => $match[0],
            ]
        ]);

//        if ($res->getStatusCode() == "200") {
//
//        }
//        $res->getHeader('content-type');
//        echo $res->getHeaderLine('content-type');

        dd($res->getBody()->getContents());
        //*/

    }
});

Route::get('/remove-duplicates', function () {
    $duplicates = DB::table('pops AS p')
        ->select('p.pop_code', 'p.dsr_code', 'p.pjp_code', DB::raw('COUNT(*) AS total'))
        ->groupBy('p.pop_code', 'p.dsr_code', 'p.pjp_code')
        ->havingRaw("COUNT(total) > 1")
        ->orderBy('total', 'DESC')
        ->get();

    if ($duplicates) {
        foreach ($duplicates as $row) {
            //dump($row);
            $first = DB::table('pops')->where('pop_code', $row->pop_code)->first();

            DB::table('pops')
                ->where('pop_code', $row->pop_code)
                ->where('id', '!=', $first->id)
                ->delete();
        }
    }

    dump('All duplicates entries from pops (table) has been removed successfully');
});

/**
 * Run these routes at daily basis (Schedule)
 */
Route::get('/assign-closed-pops-to-tm', 'TmController@assignClosedPopToTM');
Route::get('/assign-survey-to-tm', 'TmController@assignSurveyToTm');
Route::get('/assign-induction-to-tm', 'TmController@assignInductionToTm');

//region Backend / Admin Panel / Routes
## Redirect to login page
Route::get('/', function () {
    return redirect('panel/login');
});

Route::group(['prefix' => 'panel', 'middleware' => 'permission', 'namespace' => 'Backend'], function () {

    //region Auth
    ## Auth
    Route::get('/login', 'BackendAuthController@getLogin')->name('admin-login');
    Route::get('/logout', 'BackendAuthController@getLogout')->name('admin-logout');
    Route::post('/login', 'BackendAuthController@postLogin');
    //endregion

    ## Dashboard
    Route::get('/dashboard', 'BackendDashboardController@getIndex')->name('dashboard');

    //region Settings
    ## Settings
    Route::group(['prefix' => 'setting'], function () {
        Route::get('/manage', 'BackendSettingController@getManage')->name('setting-manage');
        Route::post('/update', 'BackendSettingController@postUpdate');
        Route::get('/default-currency', 'BackendSettingController@getDefaultCurrency');
        Route::put('/update-default-currency', 'BackendSettingController@putUpdateDefaultCurrency');
    });
    //endregion

    //region Account
    ## Account
    Route::group(['prefix' => 'account'], function () {
        // Manage
        Route::get('/profile', 'BackendAccountController@getProfile')->name('admin-profile');
        Route::post('/profile', 'BackendAccountController@postProfile');
        Route::post('/avatar', 'BackendAccountController@postAvatar');
        Route::put('/account-info-update/{user}', 'BackendAccountController@putInfoUpdate')->name('account-info-update.user');
        Route::put('/account-password-update/{user}', 'BackendAccountController@putPasswordUpdate')->name('account-password-update.user');
    });
    //endregion

    //region Tickets
    ## Tickets
    Route::group(['prefix' => 'ticket'], function () {
        // Create
        Route::get('/create', 'BackendTicketController@getCreate')->name('ticket-create');
        Route::post('/create', 'BackendTicketController@postCreate')->middleware('notify');

        // Update
        Route::get('/edit/{ticket}', 'BackendTicketController@getEdit')->name('ticket-edit.ticket');
        Route::put('/update/{ticket}', 'BackendTicketController@putUpdate')->name('ticket-update.ticket')->middleware('notify');

        Route::put('/status/{ticket}', 'BackendTicketController@putStatus')->name('ticket-status.ticket')->middleware('notify');

        // Approve/Reject
        Route::get('/approve/{ticket}', 'BackendTicketController@getApprove');
        Route::post('/approve', 'BackendTicketController@postApprove');
        Route::get('/reject/{ticket}', 'BackendTicketController@getReject');
        Route::post('/reject', 'BackendTicketController@postReject');

        // Detail
        Route::get('/detail/{ticket}', 'BackendTicketController@getDetail')
            ->name('ticket-detail.ticket')
            ->middleware('check_ticket_detail_permission');

        Route::get('/material-form', 'BackendTicketController@getMaterialForm');

        // Delete
        Route::post('/delete', 'BackendTicketController@postDelete')->middleware('notify');

        // Group Action
        Route::post('/group-action', 'BackendTicketController@postGroupAction');

        // Manage
        Route::get('/manage', 'BackendTicketController@getManage')->name('ticket-manage');
        Route::post('/search-data', 'BackendTicketController@postSearchData');

        // Export
        Route::post('/download', 'BackendTicketController@postDownload');

        // Permissions
        Route::get('/permissions/{ticket}', 'BackendTicketController@getPermissions')->name('permission.ticket');
        Route::post('/permissions', 'BackendTicketController@postPermissions');
    });
    //endregion

    //region Roles
    ## Roles
    Route::group(['prefix' => 'role'], function () {
        // Detail
        Route::get('/detail/{role}', 'BackendUserController@getDetail')->name('role-detail.role');

        // Create
        Route::get('/create', 'BackendRoleController@getCreate')->name('role-create');
        Route::post('/create', 'BackendRoleController@postCreate');

        // Update
        Route::get('/edit/{role}', 'BackendRoleController@getEdit')->name('role-edit.role');
        Route::put('/update/{role}', 'BackendRoleController@putUpdate')->name('role-update.role');

        // Detail
        Route::get('/detail/{role}', 'BackendRoleController@getDetail')->name('role-detail.role');

        // Delete
        Route::post('/delete', 'BackendRoleController@postDelete');

        // Group Action
        Route::post('/group-action', 'BackendRoleController@postGroupAction');

        // Manage
        Route::get('/manage', 'BackendRoleController@getManage')->name('role-manage');
        Route::post('/search-data', 'BackendRoleController@postSearchData');

        // Export
        Route::post('/download', 'BackendRoleController@postDownload');

        // Permissions
        Route::get('/permissions/{role}', 'BackendRoleController@getPermissions')->name('permission.role');
        Route::post('/permissions', 'BackendRoleController@postPermissions');
    });
    //endregion

    //region Users
    ## Users
    Route::group(['prefix' => 'user'], function () {
        // Manage
        Route::get('/manage', 'BackendUserController@getManage')->name('user-manage');
        Route::post('/search-data', 'BackendUserController@postSearchData');

        // Export
        Route::post('/download', 'BackendUserController@postDownload');

        // Create
        Route::get('/create', 'BackendUserController@getCreate')->name('user-create');
        Route::post('/create', 'BackendUserController@postCreate');

        // Update
        Route::get('/edit/{user}', 'BackendUserController@getEdit')->name('user-edit.user');
        Route::put('/update/{user}', 'BackendUserController@putUpdate')->name('user-update.user');

        // Detail
        Route::get('/detail/{user}', 'BackendUserController@getDetail')->name('user-detail.user');

        // Delete
        Route::post('/delete', 'BackendUserController@postDelete');

        // Group Action
        Route::post('/group-action', 'BackendUserController@postGroupAction');
    });
    //endregion

    //region Pages
    ## Pages
    Route::group(['prefix' => 'page'], function () {
        // Manage
        Route::get('/manage', 'BackendPageController@getManage')->name('page-manage');
        Route::post('/search-data', 'BackendPageController@postSearchData');

        // Export
        Route::post('/download', 'BackendPageController@postDownload');

        // Create
        Route::get('/create', 'BackendPageController@getCreate')->name('page-create');
        Route::post('/create', 'BackendPageController@postCreate');

        // Update
        Route::get('/edit/{page}', 'BackendPageController@getEdit')->name('page-edit.page');
        Route::put('/update/{page}', 'BackendPageController@putUpdate')->name('page-update.page');

        // Detail
        Route::get('/detail/{page}', 'BackendPageController@getDetail')->name('page-detail.page');

        // Delete
        Route::post('/delete', 'BackendPageController@postDelete');

        // Group Action
        Route::post('/group-action', 'BackendPageController@postGroupAction');
    });
    //endregion

    //region Navigation
    Route::group(['prefix' => 'navigation'], function () {
        Route::get('/settings', 'BackendNavigationController@getSettings');
        Route::post('/add-list', 'BackendNavigationController@postAddList');
        Route::post('/add-link-to-list', 'BackendNavigationController@postAddLinkToList');
        Route::post('/add-page-to-list', 'BackendNavigationController@postAddPageToList');
        Route::post('/add-list-to-location', 'BackendNavigationController@postAddListToLocation');
        Route::post('/remove-list', 'BackendNavigationController@postRemoveList');
        Route::post('/hide-list', 'BackendNavigationController@postHideList');
        Route::post('/remove-list-from-location', 'BackendNavigationController@postRemoveListFromLocation');
        Route::get('/load-module-lists', 'BackendNavigationController@getLoadModuleLists');
        Route::get('/load-module-links', 'BackendNavigationController@getLoadModuleLinks');
        Route::get('/load-module-locations', 'BackendNavigationController@getLoadModuleLocations');
        Route::post('/update-navigation-sorting', 'BackendNavigationController@postUpdateNavigationSorting');
    });
    //endregion

    //region Vehicle Types
    ## Vehicle Types
    Route::group(['prefix' => 'vehicle-type'], function () {
        // Manage
        Route::get('/manage', 'BackendVehicleTypeController@getManage')->name('vehicle-type-manage');
        Route::post('/search-data', 'BackendVehicleTypeController@postSearchData');

        // Export
        Route::post('/download', 'BackendVehicleTypeController@postDownload');

        // Create
        Route::get('/create', 'BackendVehicleTypeController@getCreate')->name('vehicle-type-create');
        Route::post('/create', 'BackendVehicleTypeController@postCreate');

        // Update
        Route::get('/edit/{id}', 'BackendVehicleTypeController@getEdit')->name('vehicle-type-edit.vehicle-type');
        Route::put('/update/{id}', 'BackendVehicleTypeController@putUpdate')->name('vehicle-type-update.vehicle-type');

        // Detail
        Route::get('/detail/{id}', 'BackendVehicleTypeController@getDetail')->name('vehicle-type-detail.vehicle-type');

        // Delete
        Route::post('/delete', 'BackendVehicleTypeController@postDelete');

        // Group Action
        Route::post('/group-action', 'BackendVehicleTypeController@postGroupAction');
    });
    //endregion

    //region Transporters
    ## Transporters
    Route::group(['prefix' => 'transporter'], function () {
        // Manage
        Route::get('/manage', 'BackendTransporterController@getManage')->name('transporter-manage');
        Route::post('/search-data', 'BackendTransporterController@postSearchData');

        // Export
        Route::post('/download', 'BackendTransporterController@postDownload');

        // Create
        Route::get('/create', 'BackendTransporterController@getCreate')->name('transporter-create');
        Route::post('/create', 'BackendTransporterController@postCreate');

        // Update
        Route::get('/edit/{id}', 'BackendTransporterController@getEdit')->name('transporter-edit.transporter');
        Route::put('/update/{id}', 'BackendTransporterController@putUpdate')->name('transporter-update.transporter');

        // Detail
        Route::get('/detail/{id}', 'BackendTransporterController@getDetail')->name('transporter-detail.transporter');

        // Delete
        Route::post('/delete', 'BackendTransporterController@postDelete');

        // Group Action
        Route::post('/group-action', 'BackendTransporterController@postGroupAction');
    });
    //endregion

    //region Sites
    ## Sites
    Route::group(['prefix' => 'site'], function () {
        // Manage
        Route::get('/manage', 'BackendSiteController@getManage')->name('site-manage');
        Route::post('/search-data', 'BackendSiteController@postSearchData');

        // Export
        Route::post('/download', 'BackendSiteController@postDownload');

        // Create
        Route::get('/create', 'BackendSiteController@getCreate')->name('site-create');
        Route::post('/create', 'BackendSiteController@postCreate');

        // Update
        Route::get('/edit/{id}', 'BackendSiteController@getEdit')->name('site-edit.site');
        Route::put('/update/{id}', 'BackendSiteController@putUpdate')->name('site-update.site');

        // Detail
        Route::get('/detail/{id}', 'BackendSiteController@getDetail')->name('site-detail.site');

        // Delete
        Route::post('/delete', 'BackendSiteController@postDelete');

        // Group Action
        Route::post('/group-action', 'BackendSiteController@postGroupAction');
    });
    //endregion

    //region Materials
    ## Materials
    Route::group(['prefix' => 'material'], function () {
        // Manage
        Route::get('/manage', 'BackendMaterialController@getManage')->name('material-manage');
        Route::post('/search-data', 'BackendMaterialController@postSearchData');

        // Export
        Route::post('/download', 'BackendMaterialController@postDownload');

        // Create
        Route::get('/create', 'BackendMaterialController@getCreate')->name('material-create');
        Route::post('/create', 'BackendMaterialController@postCreate');

        // Update
        Route::get('/edit/{id}', 'BackendMaterialController@getEdit')->name('material-edit.material');
        Route::put('/update/{id}', 'BackendMaterialController@putUpdate')->name('material-update.material');

        // Detail
        Route::get('/detail/{id?}', 'BackendMaterialController@getDetail')->name('material-detail.material');

        // Delete
        Route::post('/delete', 'BackendMaterialController@postDelete');

        // Group Action
        Route::post('/group-action', 'BackendMaterialController@postGroupAction');
    });
    //endregion

    //region Units
    ## Units
    Route::group(['prefix' => 'unit'], function () {
        // Manage
        Route::get('/manage', 'BackendUnitController@getManage')->name('unit-manage');
        Route::post('/search-data', 'BackendUnitController@postSearchData');

        // Export
        Route::post('/download', 'BackendUnitController@postDownload');

        // Create
        Route::get('/create', 'BackendUnitController@getCreate')->name('unit-create');
        Route::post('/create', 'BackendUnitController@postCreate');

        // Update
        Route::get('/edit/{id}', 'BackendUnitController@getEdit')->name('unit-edit.unit');
        Route::put('/update/{id}', 'BackendUnitController@putUpdate')->name('unit-update.unit');

        // Detail
        Route::get('/detail/{id}', 'BackendUnitController@getDetail')->name('unit-detail.unit');

        // Delete
        Route::post('/delete', 'BackendUnitController@postDelete');

        // Group Action
        Route::post('/group-action', 'BackendUnitController@postGroupAction');
    });
    //endregion

    //region Lanes
    ## Lanes
    Route::group(['prefix' => 'lane'], function () {
        // Manage
        Route::get('/manage', 'BackendLaneController@getManage')->name('lane-manage');
        Route::post('/search-data', 'BackendLaneController@postSearchData');

        // Export
        Route::post('/download', 'BackendLaneController@postDownload');

        // Create
        Route::get('/create', 'BackendLaneController@getCreate')->name('lane-create');
        Route::post('/create', 'BackendLaneController@postCreate');

        // Update
        Route::get('/edit/{id}', 'BackendLaneController@getEdit')->name('lane-edit.lane');
        Route::put('/update/{id}', 'BackendLaneController@putUpdate')->name('lane-update.lane');

        // Detail
        Route::get('/detail/{id}', 'BackendLaneController@getDetail')->name('lane-detail.lane');

        // Delete
        Route::post('/delete', 'BackendLaneController@postDelete');

        // Group Action
        Route::post('/group-action', 'BackendLaneController@postGroupAction');
    });
    //endregion

});
//endregion
