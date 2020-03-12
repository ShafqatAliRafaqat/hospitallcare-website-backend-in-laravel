<?php

// Doctor Login Routes
Route::POST('doctorapi/d1/login', 'DoctorApiControllers\DoctorLoginApiController@login');
Route::POST('doctorapi/d1/login_with_google', 'DoctorApiControllers\DoctorLoginApiController@loginWithGoogle');
Route::POST('doctorapi/d1/login_with_facebook', 'DoctorApiControllers\DoctorLoginApiController@loginWithFacebook');

// Doctor Sigup routes
Route::POST('doctorapi/d1/signup', 'DoctorApiControllers\DoctorSignUpApiController@doctorSignUp');
Route::POST('doctorapi/d1/signup_with_google', 'DoctorApiControllers\DoctorSignUpApiController@doctorSignUpWithGoogle');
Route::POST('doctorapi/d1/signup_with_facebook', 'DoctorApiControllers\DoctorSignUpApiController@doctorSignUpWithFacebook');

// doctor forget password
Route::POST('doctorapi/d1/forget_sendcode', 'DoctorApiControllers\DoctorSignUpApiController@forgetSendPhoneCode');
Route::POST('doctorapi/d1/forget_phoneverification', 'DoctorApiControllers\DoctorSignUpApiController@forgetPhoneVerification');        // doctor forget password

Route::GROUP(['namespace'=>'DoctorApiControllers','middleware' => ['auth:api'],'prefix' =>'doctorapi/'.$DoctorPrefix], function () {

// Today Appointments
Route::GET('today_appointment', 'DoctorTreatmentApiController@today_appointment');
Route::GET('upcoming_appointment', 'DoctorTreatmentApiController@upcoming_appointment');
Route::GET('cancel_appointment/{customer_id}', 'DoctorTreatmentApiController@cancel_appointment');
Route::POST('next_appointment/{customer_id}', 'DoctorTreatmentApiController@next_appointment');

// Customer History
Route::GET('treatment_history/{id}', 'DoctorTreatmentApiController@TreatmentHistory');
Route::GET('diagnostic_history/{id}', 'DoctorTreatmentApiController@DiagnosticHistory');

Route::GET('customer_doctor_notes/{id}', 'DoctorTreatmentApiController@DoctorNotes');
Route::GET('customer_allergies_history/{id}', 'DoctorTreatmentApiController@Allergies');
Route::GET('customer_risk_factor_history/{id}', 'DoctorTreatmentApiController@RiskFactor');
Route::POST('edit_customer_allergies/{id}', 'DoctorTreatmentApiController@EditAllergies');
Route::POST('edit_customer_risk_factor/{id}', 'DoctorTreatmentApiController@EditRiskFactor');
Route::POST('edit_customer_doctor_notes/{id}', 'DoctorTreatmentApiController@EditDoctorNotes');

// Doctor specilization
Route::GET('specilization','DoctorTreatmentApiController@Specilization');
Route::POST('update_specilization','DoctorTreatmentApiController@UpdateSpecilization');

// Doctor treatments
Route::GET('treatments','DoctorTreatmentApiController@Treatment');
Route::POST('update_treatments','DoctorTreatmentApiController@UpdateTreatment');

// Doctor Patients List
Route::GET('patients', 'DoctorPatientApiController@Patient');
Route::GET('patients/{id}', 'DoctorPatientApiController@PatientDetails');

// phone varificatoin and sms sending
Route::POST('sendphonecode/{id}', 'DoctorSignUpApiController@sendPhoneCode');
Route::POST('phone_verification/{id}', 'DoctorSignUpApiController@doctorPhoneVerification');

// Doctor Professional Profile
Route::GET('doctor_professional_profile', 'DoctorProfileApiController@DoctorProfessionalProfile');

// Doctor Profile
Route::GET('doctor_profile', 'DoctorProfileApiController@DoctorProfile');
Route::POST('doctor_profile', 'DoctorProfileApiController@UpdateDoctorProfile');
Route::POST('doctor_experience', 'DoctorProfileApiController@DoctorExperience');


//Doctor_Practice_Info_Api_Controller_Routes
Route::GET('practice_info','DoctorPracticeInfoApiController@PracticeInfo');
Route::GET('all_centers','DoctorPracticeInfoApiController@AllCenters');
Route::POST('add_new_center','DoctorPracticeInfoApiController@AddNewCenter');
Route::POST('save_center_first/{id}','DoctorPracticeInfoApiController@SaveCenterFirst');
Route::POST('update_primary_location/{id}','DoctorPracticeInfoApiController@UpdatePrimaryLocation');
Route::GET('appointment_settings_view/{id}','DoctorPracticeInfoApiController@AppointmentSettingsView');
Route::POST('appointment_settings/{id}','DoctorPracticeInfoApiController@AppointmentSettings');
Route::GET('visiting_times/{id}','DoctorPracticeInfoApiController@VisitingTimes');
Route::POST('update_visiting_times/{id}','DoctorPracticeInfoApiController@UpdateVisitingTimes');
Route::POST('single_time_update/{id}','DoctorPracticeInfoApiController@SingleTimeUpdate');

// CRUD at Doctor Education
Route::GET('remove_education/{id}', 'DoctorProfileApiController@RemoveDoctorEducation');
Route::POST('create_education', 'DoctorProfileApiController@CreateDoctorEducation');
Route::POST('update_education/{id}', 'DoctorProfileApiController@UpdateDoctorEducation');
Route::GET('add_new_education', 'DoctorProfileApiController@AddEducation');

// CRUD at Doctor Certification
Route::GET('doctor_certification', 'DoctorProfileApiController@DoctorCertification');
Route::GET('remove_certification/{id}', 'DoctorProfileApiController@RemoveDoctorCertification');
Route::POST('create_certification', 'DoctorProfileApiController@CreateDoctorCertification');
Route::POST('update_certification/{id}', 'DoctorProfileApiController@UpdateDoctorCertification');
Route::GET('add_new_certification', 'DoctorProfileApiController@Addcertification');


Route::GET('userdetail', 'DoctorLoginApiController@details');
Route::GET('is_approved', 'DoctorLoginApiController@is_approved');

// notifications
Route::GET('notifications','PushNotificationController@index');
Route::POST('notifications/{id}','PushNotificationController@destroy');
Route::GET('notifications/destroy_all','PushNotificationController@destroy_all');
// push notifications
Route::POST('pushNotifications/register','PushNotificationController@register');

//Customer Documents
Route::POST('documents/upload','CustomerDocumentsApiController@upload');
Route::POST('documents/show_all','CustomerDocumentsApiController@show_all');
Route::POST('documents/delete_files','CustomerDocumentsApiController@delete_files');

//logout
Route::POST('logout', 'DoctorLoginApiController@logout');
});
