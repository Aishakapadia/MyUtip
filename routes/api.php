<?php

use Illuminate\Http\Request;
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//region OUT (PHP to Mobility)
/**
 * OUT
 * Get data from ANDROID ULTRA server
 */
Route::get('/get-dsr-data', 'DsrController@getDsrData');

/**
 * I am providing data from my db survey-pops and new-inductions
 */
Route::get('/get-tm-data', 'TmController@getTmData');
//endregion

//region IN (Mobility to PHP)
/**
 * IN
 * Mobility pop-survey come from dsrs.
 */
Route::post('/survey', 'PopController@postSurvey');
Route::post('/dsr/survey', 'PopController@postDsrSurvey'); // Final
Route::post('/dsr/pop-survey', 'PopController@postDsrSurvey');

/**
 * IN
 * Mobility new-inductions come from dsrs.
 */
Route::post('/dsr/induction-survey', 'InductionController@postDsrSurvey');

/**
 * IN
 * Mobility pictures come from pop-inductions and pop-surveys
 */
Route::post('/upload', 'PopController@upload');


/**
 * IN -> TM Mobility
 * TM Responses for Survey Pops
 */
Route::post('/survey-response', 'PopController@tmResponse');

/**
 * IN -> TM Mobility
 * TM Responses for Induction Pops
 */
Route::post('/induction-response', 'InductionController@tmResponse');

//endregion