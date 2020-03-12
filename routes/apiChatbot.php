<?php

    Route::post('login', 'ApiControllers\LoginApiController@login');
    Route::post('customer/store', 'ApiControllers\CustomerApiController@store');

    Route::group(['namespace'=>'ApiControllers','middleware' => ['auth:api']], function () {

    Route::post('logout', 'LoginApiController@logout');
    // Route::get('userdetail', 'LoginApiController@details');

    Route::resource('organization', 'OrganizationApiController');
    Route::post('organization/update/{id}', 'OrganizationApiController@updates');

    Route::resource('customer', 'CustomerApiController');
    Route::get('customer_lead', 'CustomerApiController@customer_lead');
    Route::post('customer/update/{id}', 'CustomerApiController@updates');

    Route::resource('treatment', 'TreatmentApiController');
    Route::post('treatment/update/{id}', 'TreatmentApiController@updates');

    Route::resource('doctor', 'DoctorApiController');
    Route::get('doctor/treatments/{id}', 'DoctorApiController@treatments');

    Route::resource('medical-center', 'CenterApiController');

    Route::get('medical-center/treatments/{id}', 'CenterApiController@treatments');

    Route::get('medical-center/doctors/{id}', 'CenterApiController@doctors');

    // Relation Table Apis
    Route::get('center_treatment', 'RelationTableApiController@Center_Treatment');
    Route::get('center_doctor', 'RelationTableApiController@Center_Doctor');
    Route::get('doctor_treatment', 'RelationTableApiController@Doctor_Treatment');
});
