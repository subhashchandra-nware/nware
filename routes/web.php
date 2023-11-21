<?php

// use App\Http\Controllers\Api\SignupApiController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Cashier\SubscriptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LoginSignupController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResourceLocationController;
use App\Http\Controllers\ResourceTypeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth::routes();



// // Route::resource('login', LoginController::class);
Route::controller(LoginController::class)->group(function(){
    Route::get('/', 'index')->name('login');
    Route::get('login', 'index')->name('login');
    Route::get('register', 'create')->name('register');
    Route::get('logout', 'destroy')->name('logout');
    Route::post('login', 'login')->name('login.login');
    Route::post('register', 'store')->name('register.store');
    Route::get('welcome', 'welcome')->name('login.welcome');
    Route::get('select-site', 'selectSite' )->name('login.selectSite');
    Route::get('open-site/{siteName}', 'openSite')->name('login.openSite');
    Route::get('/verify-email', 'emailVerificationSend')->name('login.email.verify');
});


// Route::get('/', [LoginSignupController::class,'signIn'])->name('login');
Route::get('/sign-in', [LoginSignupController::class,'signIn']);
Route::post('/sign-in',[LoginSignupController::class,'signInPost']);
Route::get('/signup',[LoginSignupController::class,'signupView']);
Route::post('/signup',[LoginSignupController::class,'signupDetailSaved']);


// Route::get('/verify-email',[LoginSignupController::class,'emailVerificationSend'])->name('login.email.verify');
Route::get('/set-password/{token}',[LoginSignupController::class,'passwordPageView'])->name('login.setpassword');
Route::post('/set-password',[LoginSignupController::class,'setPasswordFirstTime']);
Route::post('/password-already-set',[LoginSignupController::class,'passwordAlreadySet']);
Route::get('/session-expire',[LoginSignupController::class,'sessionExpire']);
// Route::get('/logout',[LoginSignupController::class,'sessionExpire']);


// Route::get('/welcome',[LoginSignupController::class,'welcome'])->name('login.welcome');
// Route::get('/select-site',[LoginSignupController::class,'selectSite']);
// Route::get('/open-site/{siteName}',[LoginSignupController::class,'openSite']);
// Route::get('site-settings',[SiteSettingController::class,'siteSettings']);
// Route::post('site-settings',[SiteSettingController::class,'siteSettingsPost']);



Route::middleware(['auth.chcek'])->group(function () {

Route::controller(UserController::class)->group(function(){
    Route::get('user/resetpassword', 'password')->name('password.reset');
    Route::put('user/resetpassword', 'updatePassword')->name('password.update');
});

Route::resource('user', UserController::class);

// Route::get('/user',[UserController::class,'getAllUser'])->name('user.list');
// Route::get('/add-user',[UserController::class,'addUser'])->name('user.create');
// Route::post('/add-user',[UserController::class,'addUserPost'])->name('user.store');
// Route::get('/edit-user/{id}',[UserController::class,'editUser'])->name('user.edit');
// Route::post('/edit-user',[UserController::class,'editUserPost'])->name('user.update');

// Route::resource('usergroup', UserGroupController::class);
Route::get('/user-groups',[UserGroupController::class,'userGroup'])->name('usergroup.index');
Route::get('/add-user-group',[UserGroupController::class,'addUserGroup'])->name('user.group.create');
Route::post('/add-user-group',[UserGroupController::class,'addUserGroupPost'])->name('user.group.store');
Route::get('/edit-user-group/{id}',[UserGroupController::class,'editUserGroup'])->name('user.group.edit');
Route::post('/edit-user-group',[UserGroupController::class,'editUserGroupPost'])->name('user.group.update');

// Route::get('/resources',[ResourceController::class,'getAllResources']);


Route::resource('setting', SettingController::class);

Route::resource('dashboard', DashboardController::class);
Route::controller(DashboardController::class)->group(function(){
    Route::post('dashboard', 'index');
});

Route::controller(ResourceLocationController::class)->group(function(){
    Route::get('resource-location', 'getAllResourceLocation')->name('resource.location.list');
    Route::get('add-new-resource-location', 'addNewResourceLocation')->name('resource.location.create');
    Route::post('add-new-resource-location', 'addNewResourceLocationPost')->name('resource.location.store');
    Route::get('edit-resource-location/{id}', 'editResourceLocation')->name('resource.location.edit');
    Route::post('update-resource-location','updateResourceLocation')->name('resource.location.update');
});

// Route::resource('resourceType', ResourceTypeController::class);
Route::controller(ResourceTypeController::class)->group(function(){
    Route::get('resource-type', 'resourceType')->name('resource.type.list');
    Route::get('add-new-resource-type', 'addNewResourceType')->name('resource.type.create');
    Route::get('edit-resource-type/{id}', 'editResourceType')->name('resource.type.edit');
    Route::post('resource-type/{ResourceType}', 'update')->name('ResourceType.update');
    //Route::post('add-new-resource-type', 'addNewResourceTypePost');
});

Route::controller(ResourceController::class)->group(function(){
    Route::get('resource', 'getAllResource')->name('resource.resource');
    Route::get('resource/test', 'test')->name('resource.test');
    Route::get('add-resource', 'addResource')->name('resource.create');
    Route::post('add-resource', 'storeResource')->name('resource.store');
    Route::get('edit-resource/{resource}', 'editResource')->name('resource.edit');
    Route::put('update-resource/{resource}', 'updateResource')->name('resource.update');
    Route::delete('destroy-resource/{resource}', 'destroyResource')->name('resource.destroy');
});

// Route::resource('booking', BookingController::class);
// Route::controller(BookingController::class)->group(function(){});

Route::resource('book', BookController::class);
Route::controller(BookController::class)->group(function(){
    Route::post('book/resource', 'getResource')->name('getresource');
    Route::post('booked/resource/{resource}', 'getBookedResource')->name('getbookedresource');
    // Route::get('book/resource/{resource}', 'getBookedResource')->name('getbookedresource');
    Route::post('book/store', 'store')->name('bookstore');
    Route::get('book/location/{location?}', 'index')->name('book.location');
    // Route::get('book/{location}/{resource?}', 'show')->name('book.location.resource');
    Route::get('book/location/{location}/resource/{resource?}', 'show')->name('book.location.resource');

    Route::get('book/booking/{booking}/subbooking/{SubID?}', 'getBooking')->name('book.getbooking');
    Route::get('book/subbooking/{SubID}', 'getBookingBySubID')->name('book.getbooking.SubID');
    Route::put('book/booking/{booking}', 'update')->name('book.update');
    Route::post('book/accept/{book}', 'accept')->name('book.accept');
    Route::delete('book/reject/{book}', 'reject')->name('book.reject');
});

Route::controller(ReportController::class)->group(function(){
    Route::get('report/booking', 'indexBooking')->name('report.booking');
    Route::post('report/booking', 'indexBooking')->name('report.booking');
    Route::get('report/utilization', 'indexUtilization')->name('report.utilization');
    Route::post('report/utilization', 'indexUtilization')->name('report.utilization');
});
// Route::resource('report', ReportController::class);

Route::get('/emailSend',[EmailController::class,'sendEmail']);

Route::resource('subscription', SubscriptionController::class);
Route::controller(SubscriptionController::class)->group(function(){
    Route::get('subscription/{string}/{price}', [SubscriptionController::class, 'charge'])->name('goToPayment');
    Route::post('subscription/process-payment/{string}/{price}', [SubscriptionController::class, 'processPayment'])->name('processPayment');

});





Route::get('/clear-cache', function() { Artisan::call('cache:clear');});
Route::get('/config-clear', function() { Artisan::call('config:clear'); });

});
