<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['web']], function () {
    //
});

Route::group(['prefix' => 'api'], function(){
  Route::post('register', 'TokenAuthController@register');
  Route::post('authenticate', 'TokenAuthController@authenticate');
  Route::get('authenticate/user', 'TokenAuthController@getAuthenticatedUser');
  Route::post('setuserpassword', 'TokenAuthController@setUserPassword');
  Route::resource('contact', 'ContactController');
  Route::resource('partner', 'PartnerController');

  //Admin User Api's
  Route::get('getUsers','AdminUserController@getUsers');
  Route::post('adminAddUser','AdminUserController@addUser');
  Route::get('adminuser/{userid}','AdminUserController@getAdminUser');
  Route::get('adminuserrole/{userid}','AdminUserController@getAdminUserRole');
  Route::post('adminuser/{userid}','AdminUserController@updateUser');
  Route::delete('adminuser/{userid}','AdminUserController@deleteUser');


  //Subscription
  Route::post('subscribe/{userid}', 'SubscriptionController@subscribe');
  Route::post('subscribe', 'SubscriptionController@subscribe');
  Route::post('unsubscribe', 'SubscriptionController@unsubscribe');

 //UserProfile
  Route::resource('userprofile', 'UserProfileController');
  Route::post('reset-password', 'UserProfileController@resetPassword');

  //Email
  Route::resource('email-template', 'EmailController');
  Route::get('templates', 'EmailController@getEmailTemplates');
  Route::post('send-email', 'EmailController@sendEmail');

  // Services
  Route::post('create-service', 'ServiceController@store');
  Route::get('services', 'ServiceController@index');
  Route::get('services-with-product', 'ServiceController@showServiceWithProduct');
  Route::post('assign-product-to-service', 'ServiceController@assignProductToService');
  Route::get('service', 'ServiceController@getService');

  // Product
  Route::post('create-product', 'ProductController@store');
  Route::get('products', 'ProductController@index');
  Route::get('service-faq', 'ServiceController@showFaq');
  Route::get('product-with-attributes', 'ProductController@showProductWithAttribute');
  Route::post('assign-attributes-to-product', 'ProductController@assignAttributeToProduct');
  Route::get('service-feature', 'ServiceController@showFeature');
  Route::get('service-image', 'ServiceController@showHeroImages');

  // Attributes
  Route::post('create-attribute', 'AttributeController@store');
  Route::get('attributes', 'AttributeController@index');

  Route::get('attributes-with-options', 'AttributeController@showAttributeWithOption');

  // Options
  Route::post('create-option', 'OptionAndOptionChoiceController@createOption');
  Route::get('options', 'OptionAndOptionChoiceController@showOptions');
  Route::get('options-with-choices', 'OptionAndOptionChoiceController@showOptionsWithChoices');
  Route::post('assign-options-to-attribute', 'AttributeController@assignOptionToAttribute');

  // OptionChoices
  Route::post('create-choice', 'OptionAndOptionChoiceController@createOptionChoices');
  Route::get('choices', 'OptionAndOptionChoiceController@showChoices');
  Route::post('assign-choices-to-option', 'OptionAndOptionChoiceController@assignOptionTheChoice');

  // Files
  Route::post('file-add', 'FileController@store');
  Route::get('file/{filename}','FileController@show');
  Route::post('files',  'FileController@index');


  // Quote
  Route::get('get-quote-service-info', 'QuoteController@getQuoteServiceInfo');
  Route::get('get-quote-shoppingcart-info', 'QuoteController@getQuoteShoppingInfo' );
  Route::get('get-quote-material-info', 'QuoteController@getQuoteMaterialInfo' );
  Route::get('quote/get_status', 'QuoteController@getQuoteStatus' );
  Route::post('quote/assgin_csr', 'QuoteController@assginCSR' );
  Route::resource('quotes', 'QuoteController');

  // Users Apis
  Route::get('authenticate/user-exists', 'TokenAuthController@checkIfUserExists');
  Route::post('visitor', 'TokenAuthController@createVisitor');

  //Home page
  Route::get('get_cities','MGSCommonController@getCities');
  Route::get('get_services','MGSCommonController@getServices');

  //Addreaabook
  Route::resource('addressbook', 'AddressBookController');
  Route::get('get_states','AddressBookController@getStates');
  Route::get('get_countries','AddressBookController@getCountries');

  Route::get('convert-quote-to-sr','ServiceRequestController@convertQuoteToSR');
  Route::post('verify-otp','OtpVerificationController@checkotp');
  Route::post('generate-otp', 'OtpVerificationController@store');

  //Notes Module
  Route::get('notes/get_dept_list', 'NotesController@getDepartmentList');
  Route::get('notes/get_subject_list', 'NotesController@getSubjectList');
  Route::resource('notes', 'NotesController');

  /*permission and roles*/
  Route::resource('role', 'RoleController');
  Route::resource('permission', 'PermissionController');
  Route::resource('permissionrole', 'PermissionRoleController');
  Route::put('permissionrole/update', 'PermissionRoleController@update');

  // Permission Retrieval
  Route::get('get-role-with-perms', 'TokenAuthController@getAuthUserRolesWithPermission');
  Route::post('create-role', 'RolesAndPermissionController@createRole');
  Route::post('create-permission', 'RolesAndPermissionController@createPermission');
  Route::post('assign-role', 'RolesAndPermissionController@assignRole');
  Route::post('attach-permission', 'RolesAndPermissionController@attachPermission');
  Route::post('assign-user-role', 'RolesAndPermissionController@assignRole');

//Testimonial
  Route::get('gettestimonials','MGSCommonController@MgetTestimonial');

  //sitemap
  Route::get('seoCanonical', 'SeoDataController@showCanonical');

  //Migrations
  Route::get('check-old-db','UserMigrationController@checkDB');
  Route::get('migrate-user','UserMigrationController@migrateUser');
  Route::get('old-app-sr-insertion','ServiceRequestController@insertOldSR');
  Route::get('seodata','SeoDataController@show');

});

Route::group(['prefix' => 'api','middleware' => ['ability:admin,view-users']], function()
{
  Route::get('users', 'UserProfileController@index');

});

//Forgot Password Api's
Route::post('password/email', 'Auth\PasswordController@postEmail');
Route::post('password/reset', 'Auth\PasswordController@postReset');
Route::post('api/otp-forgot-password', 'Auth\PasswordController@otpPassword');


