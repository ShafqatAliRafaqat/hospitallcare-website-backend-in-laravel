<?php
// customer login routes

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::POST('customerapi/c1/login', 'CustomerApiControllers\CustomerLoginApiController@login');
Route::POST('customerapi/c1/splash', 'CustomerApiControllers\CustomerLoginApiController@splash');
Route::POST('customerapi/c1/create_new_Password/code_verification', 'CustomerApiControllers\CustomerLoginApiController@newPasswordCodeVerification');
Route::POST('customerapi/c1/create_new_Password', 'CustomerApiControllers\CustomerLoginApiController@newPassword');
Route::POST('customerapi/c1/login_with_google', 'CustomerApiControllers\CustomerLoginApiController@loginWithGoogle');
Route::POST('customerapi/c1/login_with_facebook', 'CustomerApiControllers\CustomerLoginApiController@loginWithFacebook');

// Customer signup routes

Route::POST('customerapi/c1/signup', 'CustomerApiControllers\CustomerSignUpApiController@signup');
Route::POST('customerapi/c1/signup_with_google', 'CustomerApiControllers\CustomerSignUpApiController@signUpWithGoogle');
Route::POST('customerapi/c1/signup_with_facebook', 'CustomerApiControllers\CustomerSignUpApiController@signUpWithFacebook');

// Customer forget password
Route::POST('customerapi/c1/forget_sendcode', 'CustomerApiControllers\CustomerSignUpApiController@forgetSendPhoneCode');
Route::POST('customerapi/c1/forget_phoneverification', 'CustomerApiControllers\CustomerSignUpApiController@forgetPhoneVerification');
Route::POST('customerapi/c1/resend_code', 'CustomerApiControllers\CustomerSignUpApiController@resendCode');

Route::GROUP(['namespace'=>'CustomerApiControllers','middleware' => ['auth:api'],'prefix' =>'customerapi/'.$CustomerPrefix], function () {

    // filter
    Route::GET('homesearch', 'CustomerDoctorApiController@HomeSearch');
    // Lab
    Route::GET('all_labs', 'CustomerLabApiController@all_labs');
    Route::GET('diagnostics/{id}', 'CustomerLabApiController@diagnostics');
    Route::GET('common_diagnostics/{id}', 'CustomerLabApiController@common_diagnostics');
    Route::POST('book_diagnostic_appointment', 'CustomerLabApiController@book_diagnostics');

    // Treatments
    Route::GET('top_Specializations', 'CustomerTreatmentApiController@top_Specializations');
    Route::GET('all_Specializations', 'CustomerTreatmentApiController@all_Specializations');

    // Medical Center
    Route::GET('top_centers', 'CustomerCenterApiController@top_centers');
    Route::GET('all_centers', 'CustomerCenterApiController@all_centers');
    Route::POST('fetch_center/{id}', 'CustomerCenterApiController@fetchCenter');
    Route::POST('get_center_treatments', 'CustomerCenterApiController@getCenterTreatment');

    // Doctors
    Route::POST('all_doctors', 'CustomerDoctorApiController@all_doctors');
    Route::GET('top_doctors', 'CustomerDoctorApiController@top_doctors');
    Route::GET('fetch_doctor/{id}', 'CustomerDoctorApiController@fetchDoctor');
    Route::POST('get_treatment_doctors/{id}', 'CustomerDoctorApiController@getTreatmentDoctor');

    //Book Appointment
    Route::POST('book_appointment', 'CustomerProfileApiController@book_appointment');

    //My Medical Recode
    Route::GET('my_treatments/{id}', 'CustomerProfileApiController@treatments');
    Route::GET('my_diagnostics/{id}', 'CustomerProfileApiController@diagnostics');
    Route::GET('doctor_notes/{id}', 'CustomerProfileApiController@doctorNotes');

    // Allergies
    Route::GET('all_allergies/{id}', 'CustomerProfileApiController@all_allergies');
    Route::POST('create_allergy/{id}', 'CustomerProfileApiController@create_allergy');
    Route::POST('update_allergy/{id}', 'CustomerProfileApiController@update_allergy');
    Route::GET('delete_allergy/{id}', 'CustomerProfileApiController@delete_allergy');

    // Risk Factor
    Route::GET('all_riskfactor/{id}', 'CustomerProfileApiController@all_riskfactor');
    Route::POST('create_riskfactor/{id}', 'CustomerProfileApiController@create_riskfactor');
    Route::POST('update_riskfactor/{id}', 'CustomerProfileApiController@update_riskfactor');
    Route::GET('delete_riskfactor/{id}', 'CustomerProfileApiController@delete_riskfactor');

    //Profile
    Route::GET('get_customer_profile', 'CustomerProfileApiController@getCustomerProfile');
    Route::POST('update_customer_profile', 'CustomerProfileApiController@updateCustomerProfile');

    // Treatment History
    Route::GET('treatment_history', 'CustomerProfileApiController@getTreatmentHistory');
    Route::GET('pending_treatment', 'CustomerProfileApiController@getPendingTreatment');
    Route::GET('approved_treatment', 'CustomerProfileApiController@getApprovedTreatment');
    Route::GET('cancel_treatment/{id}', 'CustomerProfileApiController@cancelTreatment');

    //Lab Appointments
    Route::GET('pendding_lab_appointments', 'CustomerProfileApiController@penddingLabAppointments');
    Route::GET('approved_lab_appointments', 'CustomerProfileApiController@approvedLabAppointments');
    Route::GET('lab_history', 'CustomerProfileApiController@getLabHistory');
    Route::POST('cancel_lab_appointments', 'CustomerProfileApiController@cancelLabAppointment');

    // Code verification
    Route::POST('phone_verification', 'CustomerSignUpApiController@customerPhoneVerification');

    //Customer Documents
    Route::POST('documents/upload/{id}','CustomerDocumentsApiController@upload');
    Route::POST('documents/show_all/{id}','CustomerDocumentsApiController@show_all');
    Route::POST('documents/delete_files/{id}','CustomerDocumentsApiController@delete_files');

    // notifications
    Route::GET('notifications','CustomerNotificationController@index');
    Route::POST('notifications/{id}','CustomerNotificationController@destroy');
    Route::GET('notifications/destroy_all','CustomerNotificationController@destroy_all');
    Route::GET('notification/setting','CustomerNotificationController@setting');
    Route::POST('notification/change_setting','CustomerNotificationController@changeNotificationsetting');

    // push notifications
    Route::POST('pushNotifications/register','PushNotificationController@register');

    //organizations
    Route::GET('all_organizations','CustomerOrganizationController@all_organizations');
    Route::GET('delete_organization','CustomerOrganizationController@delete_organization');
    Route::POST('save_organization','CustomerOrganizationController@update_customer_organization');

    // Dependent
    Route::GET('all_dependents','CustomerDependentApiController@all_dependents');
    Route::POST('search_dependent','CustomerDependentApiController@search_dependent');
    Route::POST('set_relation','CustomerDependentApiController@set_relation');
    Route::POST('save_depentent','CustomerDependentApiController@create_dependent');
    Route::POST('update_dependent/{id}','CustomerDependentApiController@update_dependent');
    Route::POST('approve_dependent/{id}','CustomerDependentApiController@approve_dependent');
    Route::POST('delete_dependent/{id}','CustomerDependentApiController@delete_dependent');

    //Dependent History
    Route::GET('dependent_history/{id}','CustomerDependentAccessApiController@dependent_history');


    //Medical Claims
    Route::GET('all_claims','CustomerMedicalClaimController@allClaims');
    Route::POST('treatments_list/{id}','CustomerMedicalClaimController@customerTreatmentHistory');
    Route::POST('new_claim','CustomerMedicalClaimController@newClaim');

    // Logout
    Route::POST('logout', 'CustomerLoginApiController@logout');
});
