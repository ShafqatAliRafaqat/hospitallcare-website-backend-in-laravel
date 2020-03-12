<?php
Route::GROUP(['namespace'=>'WebsiteApiControllers'], function () {
Route::POST('websiteapi/w1/all_doctors', 'WebDoctorController@all_doctors');
Route::GET('websiteapi/w1/top_doctors', 'WebDoctorController@top_doctors');
Route::POST('websiteapi/w1/fetch_doctor/{id}', 'WebDoctorController@fetchDoctor');
Route::POST('websiteapi/w1/create_lead', 'WebDoctorController@createLead');

Route::GET('websiteapi/w1/all_centers', 'WebCenterController@all_centers');
Route::GET('websiteapi/w1/top_centers', 'WebCenterController@top_centers');
Route::POST('websiteapi/w1/fetch_center', 'WebCenterController@fetchCenter');

Route::GET('websiteapi/w1/all_treatments', 'WebTreatmentController@all_treatments');
Route::POST('websiteapi/w1/fetch_treatment', 'WebTreatmentController@fetchTreatmentDoctor');
Route::GET('websiteapi/w1/get_treatment', 'WebTreatmentController@getTreatmentDoctor');
Route::POST('websiteapi/w1/get_center_treatments', 'WebTreatmentController@getCenterTreatment');

//AUTH
Route::POST('websiteapi/w1/phone', 'WebAuthController@phoneVarification');
Route::POST('websiteapi/w1/codeVarification', 'WebAuthController@codeVarification');
Route::POST('websiteapi/w1/signUp', 'WebAuthController@signUp');
Route::POST('websiteapi/w1/signIn', 'WebAuthController@signIn');
Route::POST('websiteapi/w1/logout', 'WebAuthController@logout');
Route::POST('websiteapi/w1/book_appointment', 'WebAuthController@book_appointment');
Route::GET('websiteapi/w1/top_specializations', 'WebTreatmentController@top_Specializations');
// Doctor AUTH
Route::POST('websiteapi/w1/doctor_signIn', 'WebDoctorAuthController@doctorSignIn');
Route::POST('websiteapi/w1/doctor_signUp', 'WebDoctorAuthController@doctorSignUp');
Route::POST('websiteapi/w1/doctorCodeVarification', 'WebDoctorAuthController@doctorCodeVarification');
//forget password for Doctor
Route::POST('websiteapi/w1/forgotPasswordDoctor', 'WebDoctorAuthController@forgotPasswordDoctor');
Route::POST('websiteapi/w1/newPasswordDoctor', 'WebDoctorAuthController@newPasswordDoctor');
Route::POST('websiteapi/w1/doctorForgetCodeVarification', 'WebDoctorAuthController@doctorForgetCodeVarification');


//forget password for Customer
Route::POST('websiteapi/w1/forgetPhoneVarification', 'WebAuthController@forgetPhoneVarification');
Route::POST('websiteapi/w1/newPassword', 'WebAuthController@newPassword');
Route::POST('websiteapi/w1/filter_doctor', 'WebSearchController@doctorFilter');
//Search Routes
Route::GET('websiteapi/w1/homesearch', 'WebSearchController@HomeSearch');

//Feedbacks
Route::POST('websiteapi/w1/send_feedback', 'WebAuthController@sendFeedback');

// Blogs
Route::GET('websiteapi/w1/blogs', 'WebBlogController@AllBlogs');
Route::GET('websiteapi/w1/blog/{id}', 'WebBlogController@blog');
Route::GET('websiteapi/w1/blog_category/{id}', 'WebBlogController@BlogCategory');

// Vlogs
Route::GET('websiteapi/w1/vlogs', 'WebMediaController@AllVlogs');
Route::GET('websiteapi/w1/vlog/{id}', 'WebMediaController@Vlog');

// Media
Route::GET('websiteapi/w1/videos', 'WebMediaController@AllVideos');
Route::GET('websiteapi/w1/video/{id}', 'WebMediaController@Video');

});

Route::GROUP(['namespace'=>'WebsiteApiControllers','middleware' => ['auth:api']], function () {
//Profile
Route::GET('websiteapi/w1/fetch_customer/{id}', 'WebCustomerController@fetchCustomer');
Route::POST('websiteapi/w1/update_customer_profile', 'WebCustomerController@updateCustomer');
//update risk factor
Route::GET('websiteapi/w1/get_doctornotes/{id}', 'WebCustomerController@getDoctorNotes');
Route::GET('websiteapi/w1/get_riskfactor/{id}', 'WebCustomerController@getRiskfactor');
Route::POST('websiteapi/w1/update_riskfactor', 'WebCustomerController@updateRiskfactor');

//update Allergies
Route::GET('websiteapi/w1/get_allergies/{id}', 'WebCustomerController@getAllergies');
Route::POST('websiteapi/w1/update_allergies', 'WebCustomerController@updateAllergies');

Route::GET('websiteapi/w1/cancel_appointments/{id}', 'WebCustomerController@cancelAppointment');

//Lab Appointments
Route::GET('websiteapi/w1/get_lab_appointments', 'WebCustomerController@getLabAppointments');
Route::GET('websiteapi/w1/get_lab_history', 'WebCustomerController@getLabHistory');
//Treatment Appointments
Route::GET('websiteapi/w1/approved_treatment_appointments', 'WebCustomerController@approvedTreatmentAppointments');
Route::GET('websiteapi/w1/pending_treatment_appointments', 'WebCustomerController@pendingTreatmentAppointments');
Route::GET('websiteapi/w1/get_treatment_history', 'WebCustomerController@getTreatmentHistory');

});
