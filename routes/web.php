<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/cache', function(){
    Artisan::call(' cache ','clear');
});

Route::group(['middleware'=>['auth','role:admin|coordinator|servaid|think|organization_admin|center_admin|doctor_admin|doctor_profile|doctor_specialization|coordinator_plus'],'namespace'=>'AdminControllers','prefix' => 'admin'], function(){
	Route::get('/', 'DashboardController@index')->name('adminDashboard');

	Route::resource('treatment', 'TreatmentController');
	Route::resource('procedure', 'ProcedureController');
	Route::resource('medical',   'MedicalController');
	Route::resource('doctors',   'DoctorController');
	Route::resource('center_users',   'CenterUsersController');
	Route::resource('doctor_users',   'DoctorUsersController');
	Route::resource('videos',    'VideoController');
	Route::resource('articles',  'ArticleController');
	Route::resource('whitepaper',  'WhitepaperController');
	Route::resource('packages',  'PackageController');
	Route::resource('testimonials',  'TestimonialController');
	Route::resource('roles',  'RoleController');
	Route::resource('users',  'UsersController');
	Route::resource('customers', 'CustomerController');
	Route::resource('employees', 'EmployeeController');
	//Route of Dependents on Organizational Dashboard
	Route::resource('dependent', 'DependentController');
	//Route of Dependents on Admin Dashboard
	Route::resource('dependents', 'DependentsController');
	Route::resource('status', 'StatusController');
	Route::resource('permissions',  'PermissionsController');
	Route::resource('faqs', 'FaqController');
	Route::resource('organization', 'OrganizationController');
	Route::resource('notuploaded', 'TempCustomerController');
	Route::resource('webleads', 'WebsiteLeadsController');
	Route::resource('clients', 'CenterClientController');
	Route::resource('doctorclients', 'DoctorClientController');
	Route::resource('labs',   'LabController');
	Route::resource('diagnostics','DiagnosticController');
    Route::resource('doctorschedule','DoctorScheduleController');
    Route::resource('pendingappointments','PendingAppointmentsController');
	Route::resource('pendingdiagnostics','PendingDiagnosticsController');
	// Media Hub
    Route::resource('blogcategory','BlogCategoryController');
    Route::resource('blogs','BlogController');
    Route::resource('media','MediaController');
    Route::resource('vlogs','VlogsController');

    Route::GET('clients/documents_upload/{id}','DoctorClientController@clientsDocumentsEdit')->name('documents_upload');
    Route::POST('clients/documents_upload/{id}','DoctorClientController@clientsDocumentsUpload')->name('Upload_Customer_files');

	// update pending appointment
    Route::POST('/update_pending_appointment/{id}', 'PendingAppointmentsController@updatePendingAppointment')->name('update_pending_appointment');
	//Corporate Leads Route
	Route::GET('/corporate', 'CustomerController@corporate')->name('corporate_customers');

    //Customer Treatment History Route
    Route::GET('/treatment_history/{id}', 'CustomerController@TreatmentHistory')->name('treatment_history');
    Route::POST('/treatment_to_history/{id}', 'CustomerController@TreatmentToHistory')->name('treatment_to_history');
    Route::POST('/next_appointment_date', 'CustomerController@nextAppointment')->name('nextAppointment');
    Route::POST('/create_new_appointment/{id}', 'CustomerController@createNewAppointment')->name('createNewAppointment');


    //Employee/Dependent Treatment History Route
    Route::GET('/emp_treatment_history/{id}', 'EmployeeController@TreatmentHistory')->name('emp_treatment_history');

    //Card Holder Customers
    Route::GET('/CardHolders', 'CustomerController@indexCardHolders')->name('indexCardHolders');

    //Customer Diagnostic History Route
	Route::GET('/diagnostic_history/{id}', 'CustomerController@DiagnosticHistory')->name('diagnostic_history');
	Route::POST('/diagnostic_to_history/{id}', 'CustomerController@DiagnosticToHistory')->name('diagnostic_to_history');
    //Customer Diagnostic Routes
    Route::POST('/new_diagnostic_appointment/{id}', 'CustomerController@NewDiagnosticAppointment')->name('NewDiagnosticAppointment');
    Route::GET('/edit_diagnostic_appointment/{id}', 'CustomerController@EditDiagnosticAppointment')->name('EditDiagnosticAppointment');

    //Doctor Profiling by Doctor Himself.
    Route::PUT('doctor_general_info/{id}', 'DoctorPanelProfileController@doctorGeneralInfo')->name('doctor_general_info');
    Route::GET('doctor_specialization', 'DoctorPanelProfileController@doctorEditSpecialization')->name('doctor_edit_specialization');
    Route::POST('doctor_specialization_update', 'DoctorPanelProfileController@doctorSpecializationUpdate')->name('doctor_specialization_update');
    Route::GET('doctor_edit_schedules', 'DoctorPanelProfileController@doctorEditSchedules')->name('doctor_edit_schedules');

    //Doctor Schedule
    Route::GET('doctor_schedules/{id}', 'DoctorController@View_Schedules')->name('ViewSchedules');
    Route::POST('doctor_schedules_edit/{id}', 'DoctorController@Edit_Schedules')->name('doctor_schedule_edit');
    Route::PUT('doctor_schedule_update/{id}', 'DoctorController@Update_Center_schedule')->name('doctor_schedule_update');
    Route::POST('doctor_schedule_delete/{id}', 'DoctorController@Delete_Center_schedule')->name('doctors_centers_destroy');

	//General Leads Route
	Route::GET('/general', 'CustomerController@GeneralCustomers')->name('general_customers');

	//Canceled Appointments
	Route::GET('/canceled_appointments', 'CustomerController@CanceledAppointments')->name('canceled_appointments');

	//On Going Appointments Route
	Route::GET('/ongoing_procedures', 'CustomerController@OngoingProcedures')->name('ongoing_procedures');

	// List of No Show Customer
	Route::GET('/no_show_customers', 'CustomerController@NoShowCustomers')->name('no_show_customers');

    //Doctorpanel Doctor Profile
    Route::GET('/doctor_profile', 'DoctorScheduleController@doctor_profile')->name('doctor_profile');

    //Doctorpanel Doctor Profile Edit
    Route::GET('/doctor/profile_edit', 'DoctorScheduleController@doctor_profile_edit')->name('doctor_profile_edit');

    //Doctorpanel Doctor Profile Save
    Route::post('/save_doctor_profile/{id}', 'DoctorScheduleController@save_doctor_profile')->name('save_doctor_profile');

    //Temp Centers (Approval)
    Route::GET('/temporary_centers', 'MedicalController@Tempcenter')->name('temp_centers');
    Route::GET('/temporary_centers/{id}', 'MedicalController@approve_center_edit')->name('approve_center_edit');
    Route::PUT('/approve_center/update/{id}', 'MedicalController@approve_center')->name('approve_center');

    //Temp Doctors (Approval)
    Route::GET('/temporary_doctors', 'DoctorController@Tempdoctors')->name('temp_doctors');
    Route::GET('/temporary_doctors/{id}', 'DoctorController@approve_doctors_edit')->name('approve_doctors_edit');
    Route::POST('/approve_doctor/update/{id}', 'DoctorController@approve_doctor')->name('approve_doctor');

    //Facebook Temp Leads Routes
	Route::GET('/temporary_leads', 'CustomerController@TempLeads')->name('temp_leads');
	Route::GET('/temporary_leads/edit/{id}', 'CustomerController@EditLeads')->name('edit_leads');
	Route::POST('/temporary_leads/update/{id}', 'CustomerController@UpdateLeads')->name('update-leads');
	Route::POST('/temporary_leads/delete', 'CustomerController@DestroyLeads')->name('destroy-leads');

	//Web Leads User table
	Route::GET('/web_users', 'WebsiteLeadsController@WebUsers')->name('web_users');

	//Appointment Path for Center cusstomers
	Route::GET('/upcoming/appointment',  'CenterClientController@upcoming')->name('upcoming-appointment');
	Route::GET('/today/appointment',  'CenterClientController@today')->name('today-appointment');
	Route::GET('/previous/appointment',  'CenterClientController@previous')->name('previous-appointment');

	//Appointment Path for Doctor cusstomers
	Route::GET('/upcoming-appointments',  'DoctorClientController@upcoming')->name('doctor-upcoming-appointment');
	Route::GET('/today-appointments',  'DoctorClientController@today')->name('doctor-today-appointment');
	Route::GET('/previous-appointments',  'DoctorClientController@previous')->name('doctor-previous-appointment');
	Route::GET('/appointment-history',  'DoctorClientController@appointment_history')->name('doctor-appointment-history');
	Route::GET('/customer_treatment_history/{id}',  'DoctorClientController@TreatmentHistory')->name('customer_treatment_history');
	Route::POST('/appointment_created_by_doctor/{id}',  'DoctorClientController@createNewAppointment')->name('appointmentCreatedByDoctor');
	//customer Appointment canceled by doctor
	Route::post('/cancel-customer-appointment/{id}', 'DoctorClientController@cancelCustomerAppointment')->name('cancel-customer-appointment');

	//Doctor Controlling Customers Appointments
	Route::post('doctorschedule/reschedule/{id}','DoctorClientController@Edit_Appointment')->name('editAppointment');
	// doctor can add new doctor notes
	Route::post('doctor/doctor_notes/{id}','DoctorClientController@addCustomerDoctorNotes')->name('addCustomerDoctorNotes');
	// Doctor can add new allergies of customer
	Route::post('doctor/customer_allergies/{id}','DoctorClientController@addCustomerAllergiesNotes')->name('addCustomerAllergiesNotes');
	// Doctor can add new risk factor of customer
	Route::post('doctor/customer_risk_factor/{id}','DoctorClientController@addCustomerRiskFactorNotes')->name('addCustomerRiskFactorNotes');

	// Admin can add new notes from customer detail page
 	Route::post('customer/notes/{id}','CustomerController@editCustomerNotes')->name('editCustomerNotes');
	// Admin can add new allergies of customer detail page
	Route::post('custmer/customer_allergies/{id}','CustomerController@editCustomerAllergiesNotes')->name('editCustomerAllergiesNotes');
	// Admin can add new risk factor of customer detail page
	Route::post('customer/customer_risk_factor/{id}','CustomerController@editCustomerRiskFactorNotes')->name('editCustomerRiskFactorNotes');
	// Pending Request to link with Organization
	Route::post('approve_pending_customer','OrganizationController@approve_pending_customer')->name('approve_pending_customer');
	Route::post('reject_pending_customer','OrganizationController@reject_pending_customer')->name('reject_pending_customer');

	//Organization path
	Route::GET('/dependent/create/{id}', 'DependentsController@createdependent')->name('depend');
	Route::GET('/employee/informed',  'EmployeeStatusController@informed')->name('employee-informed');
	Route::GET('/employee/got-appointment',  'EmployeeStatusController@got_appointment')->name('employee-got-appointment');
	Route::GET('/employee/took-appointment',  'EmployeeStatusController@took_appointment')->name('employee-took-appointment');
	Route::GET('/employee/took-treatment',  'EmployeeStatusController@took_treatment')->name('employee-took-treatment');
	Route::GET('/employee/no-contact',  'EmployeeStatusController@no_contact')->name('employee-no-contact');
	// Employess Stats
	Route::GET('/employee/today',  'EmployeeStatusController@today_stats')->name('today-employess');
	Route::GET('/employee/this-week',  'EmployeeStatusController@this_week_stats')->name('this-week-employess');
	Route::GET('/employee/previous-week',  'EmployeeStatusController@previous_week_stats')->name('previous-week-employess');
	Route::GET('/employee/this-month',  'EmployeeStatusController@this_month_stats')->name('this-month-employess');
	Route::GET('/employee/this-year',  'EmployeeStatusController@this_year_stats')->name('this-year-employess');
	//Employee Diagnostic
	Route::GET('/upcoming/diagnostic',  'EmployeeStatusController@upcoming')->name('upcoming-diagnostic');
	Route::GET('/today/diagnostic',  'EmployeeStatusController@today')->name('today-diagnostic');
	Route::GET('/previous/diagnostic',  'EmployeeStatusController@previous')->name('previous-diagnostic');
	// Employess
	Route::GET('/active-employees',  'EmployeeController@activeEmployees')->name('active-employees');
	Route::GET('/pending-employees',  'EmployeeController@pendingEmployees')->name('pending-employees');

    //Medical Claims of Employees
    Route::GET('/pending_claims','EmployeeMedicalClaimController@pendingClaims')->name('pending_claims');
    Route::GET('/all_claims','EmployeeMedicalClaimController@allClaims')->name('all_claims');
    Route::POST('/delete_claim/{id}','EmployeeMedicalClaimController@deleteClaim')->name('delete_claim'); //Soft Delete
    Route::GET('/delete_claim','EmployeeMedicalClaimController@showDeletedClaim')->name('show_deleted_claim'); //Show Deleted Claims
    Route::POST('/restore_claim/{id}','EmployeeMedicalClaimController@restoreDeletedClaim')->name('restore_claim'); //Show Deleted Claims
    Route::POST('/force_delete_claim/{id}','EmployeeMedicalClaimController@forceDeleteClaim')->name('force_delete_claim'); //Permanent Delete
    Route::POST('/edit_claim/{id}','EmployeeMedicalClaimController@editClaim')->name('edit_claim');
    Route::POST('/show/{id}','EmployeeMedicalClaimController@show')->name('view_claim');
    Route::POST('/update_claim/{id}','EmployeeMedicalClaimController@updateClaim')->name('update_claim');

    // Deleted Data
    Route::POST('/delete/status/{id}',  'StatusController@per_delete')->name('status_per_delete');
    Route::POST('/delete/customer/{id}',  'CustomerController@per_delete')->name('customer_per_delete');
    Route::POST('/delete/treatment/{id}',  'TreatmentController@per_delete')->name('treatment_per_delete');
    Route::POST('/delete/procedure/{id}',  'ProcedureController@per_delete')->name('procedure_per_delete');
    Route::POST('/delete/doctor/{id}',  'DoctorController@per_delete')->name('doctor_per_delete');
    Route::POST('/delete/center/{id}',  'MedicalController@per_delete')->name('center_per_delete');
    Route::POST('/delete/lab/{id}',  'LabController@per_delete')->name('lab_per_delete');
    Route::POST('/delete/diagnostic/{id}',  'DiagnosticController@per_delete')->name('diagnostic_per_delete');
    Route::POST('/delete/user/{id}',  'UsersController@per_delete')->name('user_per_delete');
	Route::POST('/delete/organization/{id}',  'OrganizationController@per_delete')->name('organization_per_delete');
	Route::POST('/delete/blog_category/{id}',  'BlogCategoryController@per_delete')->name('blogcategory_per_delete');
	Route::POST('/delete/blog/{id}',  'BlogController@per_delete')->name('blog_per_delete');
	Route::POST('/delete/media/{id}',  'MediaController@per_delete')->name('media_per_delete');
	Route::POST('/delete/vlog/{id}',  'VlogsController@per_delete')->name('vlog_per_delete');


    //Show Deleted Data
    Route::GET('/show_status',  'StatusController@show_deleted')->name('status_show_deleted');
    Route::GET('/show_customer',  'CustomerController@show_deleted')->name('customer_show_deleted');
    Route::GET('/show_treatment',  'TreatmentController@show_deleted')->name('treatment_show_deleted');
    Route::GET('/show_procedure',  'ProcedureController@show_deleted')->name('procedure_show_deleted');
    Route::GET('/show_doctor',  'DoctorController@show_deleted')->name('doctor_show_deleted');
    Route::GET('/show_center',  'MedicalController@show_deleted')->name('center_show_deleted');
    Route::GET('/show_lab',  'LabController@show_deleted')->name('lab_show_deleted');
    Route::GET('/show_diagnostic',  'DiagnosticController@show_deleted')->name('diagnostic_show_deleted');
    Route::GET('/show_user',  'UsersController@show_deleted')->name('user_show_deleted');
	Route::GET('/show_organization', 'OrganizationController@show_deleted')->name('organization_show_deleted');
	Route::GET('/blogcategory_show_deleted',  'BlogCategoryController@show_deleted')->name('blogcategory_show_deleted');
	Route::GET('/blog_show_deleted',  'BlogController@show_deleted')->name('blog_show_deleted');
	Route::GET('/media_show_deleted',  'MediaController@show_deleted')->name('media_show_deleted');
	Route::GET('/vlog_show_deleted',  'VlogsController@show_deleted')->name('vlog_show_deleted');

    // Restore Data
    Route::POST('/restore/status/{id}',  'StatusController@restore')->name('status_restore');
    Route::POST('/restore/customer/{id}',  'CustomerController@restore')->name('customer_restore');
    Route::POST('/restore/treatment/{id}',  'TreatmentController@restore')->name('treatment_restore');
    Route::POST('/restore/procedure/{id}',  'ProcedureController@restore')->name('procedure_restore');
    Route::POST('/restore/doctor/{id}',  'DoctorController@restore')->name('doctor_restore');
    Route::POST('/restore/center/{id}',  'MedicalController@restore')->name('center_restore');
    Route::POST('/restore/lab/{id}',  'LabController@restore')->name('lab_restore');
    Route::POST('/restore/diagnostic/{id}',  'DiagnosticController@restore')->name('diagnostic_restore');
    Route::POST('/restore/user/{id}',  'UsersController@restore')->name('user_restore');
	Route::POST('/restore/organization/{id}',  'OrganizationController@restore')->name('organization_restore');
	Route::POST('/restore/blog_category/{id}',  'BlogCategoryController@restore')->name('blogcategory_restore');
	Route::POST('/restore/blog/{id}',  'BlogController@restore')->name('blog_restore');
	Route::POST('/restore/media/{id}',  'MediaController@restore')->name('media_restore');
	Route::POST('/restore/vlog/{id}',  'VlogsController@restore')->name('vlog_restore');

	/* Center Article Route */
	Route::patch('/center/detail', 'CenterController@article')->name('center-detail');
	Route::POST('/center/{id}/detail-edit', 'CenterController@edit_article')->name('edit-article');

	/* Ajax Controller Routes */
	Route::POST('/procedures/fetch', 'AjaxController@fetchProcedures')->name('getProcedures');
	Route::POST('/center_treatments/fetch', 'AjaxController@getCenterDoctorTreatments')->name('getCenterDoctorTreatments');
    Route::POST('/centers/fetch', 'AjaxController@fetchCenters')->name('getCenters');
	Route::POST('/centersByLocation/fetch', 'AjaxController@fetchCentersByLocation')->name('getCentersByLocation');
	Route::POST('/doctors/fetch', 'AjaxController@fetchDoctors')->name('getDoctors');
    Route::POST('/treatments/fetch', 'AjaxController@fetchTreatments')->name('getTreatments');
    //Treatments Fetched on the basis of Spcializations selected by the Doctor in profile making
	Route::POST('/treatments/fetchMultipleTreatments', 'AjaxController@fetchMultipleTreatments')->name('getMultipleTreatments');
	Route::POST('/centertreatments/fetch', 'AjaxController@fetchCenterTreatments')->name('getCenterTreatments');
	Route::POST('/costs1/fetch', 'AjaxController@fetchMedTreatmentCost1')->name('getCost1');
	Route::POST('/costs2/fetch', 'AjaxController@fetchMedTreatmentCost2')->name('getCost2');
	Route::POST('/costs/fetch', 'AjaxController@fetchDocTreatmentCost')->name('getDocCost');
	Route::POST('/lab/fetch', 'AjaxController@fetchLabs')->name('getLabs');
	Route::POST('/diagnostics/fetch', 'AjaxController@fetchDiagnostics')->name('getDiagnostics');
	Route::POST('/diagnosticcost/fetch', 'AjaxController@fetchDiagnosticCost')->name('getDiagnosticCost');
	Route::POST('/diagnosticcost2/fetch', 'AjaxController@fetchDiagnosticCost2')->name('getDiagnosticCost2');
	Route::POST('/schedule/fetch', 'AjaxController@fetchDoctorSchedule')->name('getDoctorSchedule');
	Route::POST('/organizations/fetch', 'AjaxController@fetchOrganizations')->name('getOrganization');
	Route::GET('/import', 'AjaxController@importIndex')->name('importData');
	Route::POST('/import/customers', 'AjaxController@importCustomers')->name('customer-import');
	Route::POST('/livesearch/treatments', 'AjaxController@treatmentLiveSearch')->name('treatment-live-search');
	Route::POST('/livesearch/procedure', 'AjaxController@procedureLiveSearch')->name('procedure-live-search');
	Route::POST('/livesearch/diagnostic', 'AjaxController@diagnosticLiveSearch')->name('diagnostic-live-search');
	Route::GET('/importemp', 'AjaxController@importEmployeeIndex')->name('importEmployee');
	Route::POST('/importemp/employees', 'AjaxController@importEmployees')->name('employees-import');
    Route::GET('/importpending', 'AjaxController@importPendingIndex')->name('importPending');
    Route::POST('/importpending/leads', 'AjaxController@importPendingLeads')->name('pending-import');
    Route::GET('/seo-doctor', 'AjaxController@seoDoctor')->name('seo-doctor');
    Route::POST('/importDoctorSeo', 'AjaxController@importDoctorSeo')->name('import-doctor-seo');
	Route::POST('/patient-coordinator-performance', 'AjaxController@patientCoordinatorPerformance')->name('patient-coordinator-performance');
	/* Servaid Routes Controller */
	Route::GET('/servaid-orders', 'ServaidController@index')->name('servaid-orders');

    //Search Customer on Index
    Route::POST('customers-search', 'CustomerController@customerSearch')->name('customer-search');
    Route::POST('/customer_search_destroy/{id}', 'CustomerController@search_destroy')->name('customer_search_destroy');

	/* Setting Route */
	Route::resource('settings', 'SettingsController');

	// EXPORT DATA on EXCEL
	Route::POST('exports', 'ExportController@exportToExcel')->name('exports');
	Route::POST('export', 'ExportController@export')->name('export');
	Route::POST('exportbystatus', 'ExportController@exportbystatus')->name('exportbystatus');
	//Excel Report generation against Centers, Patient Owner and Statuses
	Route::POST('exportbycenter', 'ExportController@exportbycenter')->name('exportbycenter');
	//Excel Report against Organization
	Route::POST('exportbyOrganization', 'ExportController@exportbyOrganization')->name('exportbyOrganization');
	Route::POST('generatereport', 'ReportController@report')->name('generatereport');
	// Route::POST('export/{export}', 'ExportController@export')->name('export'); //FOR ID
	Route::POST('clientsreport', 'ReportController@clientsreport')->name('clientsreport');
	Route::POST('doctorclientsreport', 'ReportController@doctorclientsreport')->name('doctorclientsreport');

    //Excel Report generation of All Doctors
    Route::POST('exportbyDoctor', 'ExportController@exportbyDoctor')->name('exportbyDoctor');

	//View all the customers in given duration on screen
	Route::GET('created_today', 'ReportController@CreatedToday')->name('CreatedToday');
	Route::GET('updated_today', 'ReportController@UpdatedToday')->name('UpdatedToday');
	Route::GET('created_this_week', 'ReportController@CreatedThisWeek')->name('CreatedThisWeek');
	Route::GET('updated_this_week', 'ReportController@UpdatedThisWeek')->name('UpdatedThisWeek');
	Route::GET('created_previous_week', 'ReportController@CreatedPreviousWeek')->name('CreatedPreviousWeek');
	Route::GET('updated_previous_week', 'ReportController@UpdatedPreviousWeek')->name('UpdatedPreviousWeek');
	Route::GET('created_this_month', 'ReportController@CreatedThisMonth')->name('CreatedThisMonth');
	Route::GET('updated_this_month', 'ReportController@UpdatedThisMonth')->name('UpdatedThisMonth');
	Route::GET('created_this_year', 'ReportController@CreatedThisYear')->name('CreatedThisYear');
	Route::GET('updated_this_year', 'ReportController@UpdatedThisYear')->name('UpdatedThisYear');
	Route::GET('cold_leads', 'ReportController@ColdLeads')->name('ColdLeads');
	Route::GET('warm_leads', 'ReportController@WarmLeads')->name('WarmLeads');
	Route::GET('hot_leads', 'ReportController@HotLeads')->name('HotLeads');
	Route::GET('customer_leads', 'ReportController@CustomerLeads')->name('CustomerLeads');
	Route::GET('no_contact_leads', 'ReportController@NoContactLeads')->name('NoContactLeads');
	Route::GET('dont_call_leads', 'ReportController@DontCallLeads')->name('DontCallLeads');

});

    Auth::routes();
    Route::get('forget_password/{id}', 'Auth\ForgotPasswordController@forgetPasswordForm')->name('forget_password');
    Route::post('reset_doctor_password', 'Auth\ForgotPasswordController@resetDoctorPassword')->name('reset_doctor_password');
    Route::post('reset_customer_password', 'Auth\ForgotPasswordController@resetCustomerPassword')->name('reset_customer_password');

	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/', 'UserController@index')->name('index');
	Route::get('/leadership-team', 'UserController@about')->name('about');
	Route::get('/overview', 'UserController@promise')->name('promise');
	Route::get('/leadership', 'UserController@leadership')->name('leadership');
	Route::get('/blogs', 'UserController@blogs')->name('blogs');
	Route::get('/products/claims-management', 'UserController@claims')->name('claims-management');
	Route::get('/products/health-management', 'UserController@health')->name('health-management');
	Route::get('/products/concierge-card', 'UserController@concierge')->name('concierge');
	Route::get('/products/medical-tourism', 'UserController@tourism')->name('tourism');
	Route::get('/products/csr', 'UserController@csr')->name('csr');
	Route::get('/contact', 'UserController@contact')->name('contact');
	Route::post('/contact', 'UserController@contact_form')->name('contact-form');
	Route::get('/procedures/all', 'UserController@all_procedures')->name('all-procedures');
	Route::get('procedure/{id}/{slug}', 'UserController@procedure_detail')->name('procedure-detail');
	Route::get('sub-procedure/{id}/{slug}', 'UserController@sub_procedure')->name('sub-procedure');
	Route::get('blogs/{id}/{slug}', 'UserController@article')->name('article');

	/* Treatments and Results */
	Route::get('/search/treatment/', 'UserController@search_treatment')->name('search-procedures');
	Route::post('/search-treatment-slug', function(){
		$term = request()->term;
		$term = str_slug($term.'-');
		if ($term == '') {
			return redirect()->route('all-procedures');
		}
		return redirect("/search-treatment/$term");
	})->name('search-procedures-post');
	Route::get('/search-treatment/{slug}', 'UserController@search_result')->name('search-procedures-result');


	/* Blogs and Results */
	Route::get('/blogs/all', 'UserController@all_articles')->name('all-articles');
	Route::get('/search/blogs/', 'UserController@search_blogs')->name('search-blogs');
	Route::post('/search-blogs/', function(){
		$term = request()->term;
		$term = str_slug($term.'-');
		if ($term == '') {
			return redirect()->route('all-articles');
		}
		return redirect()->route('search-blogs-result', $term );
	})->name('search-blogs-post');
	Route::get('/search-blogs/{slug}', 'UserController@search_blog_result')->name('search-blogs-result');

	Route::group(['prefix' => 'docs'], function(){
		Route::get('/', 'DocsController@index')->name('api-list');
		Route::get('/{id}', 'DocsController@detail')->name('api-detail');
		
	});