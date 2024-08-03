<?php

use App\Http\Controllers\DatatablesController;
use App\Http\Controllers\District\districtControler;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Products\ImagesContoller;
use App\Http\Controllers\settings\settingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Suppliers\supplierController;
use App\Http\Controllers\testController;
use App\Http\Controllers\Wilayas\WilayasController;
use App\Models\Subcategory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Spatie\CollectionMacros\Macros\Rotate;

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

$controller_path = 'App\Http\Controllers';

// Main Page Route
Route::get('/', 'App\Http\Controllers\dashboard\Analytics@index')->name('dashboard-analytics')->middleware('auth');
Route::get('/privacy_policy','App\Http\Controllers\DocumentationController@public');

Route::group(['middleware' => ['auth']], function () {
  Route::get('/version', 'App\Http\Controllers\VersionController@index')->name('version');
  Route::post('/version/update', 'App\Http\Controllers\VersionController@update')->name('version.update');
  Route::get('/stats', 'App\Http\Controllers\dashboard\Analytics@stats')->name('stats');
  Route::get('/category/browse', 'App\Http\Controllers\CategoryController@index')->name('category-browse');
  Route::get('/subcategory/browse', 'App\Http\Controllers\SubcategoryController@index')->name('subcategory-browse');
  Route::get('/product/browse', 'App\Http\Controllers\ProductController@index')->name('product-browse');
  Route::get('/order/{id}/items', 'App\Http\Controllers\ItemController@index')->name('order-items');
  Route::get('/category/list', 'App\Http\Controllers\DatatablesController@categories')->name('category-list');
  Route::post('/subcategory/list', 'App\Http\Controllers\DatatablesController@subcategories')->name('subcategory-list');
  Route::get('/family/list', 'App\Http\Controllers\DatatablesController@families')->name('family-list');
  Route::get('/offer/list', 'App\Http\Controllers\DatatablesController@offers')->name('offer-list');
  Route::post('/product/list', 'App\Http\Controllers\DatatablesController@products')->name('product-list');
  Route::get('/section/list', 'App\Http\Controllers\DatatablesController@sections')->name('section-list');
  Route::post('/order/list', 'App\Http\Controllers\DatatablesController@orders')->name('order-list');
  Route::post('/item/list', 'App\Http\Controllers\DatatablesController@items')->name('item-list');
  Route::get('/driver/list', 'App\Http\Controllers\DatatablesController@drivers')->name('driver-list');
  Route::get('/user/list', 'App\Http\Controllers\DatatablesController@users')->name('user-list');
  Route::get('/notice/list', 'App\Http\Controllers\DatatablesController@notices')->name('notice-list');
  Route::get('/ad/list', 'App\Http\Controllers\DatatablesController@ads')->name('ad-list');
  Route::post('/client/list', 'App\Http\Controllers\DatatablesController@clients')->name('client-list');

  Route::get('/order/browse', 'App\Http\Controllers\OrderController@index')->name('order-browse');
  Route::get('/offer/browse', 'App\Http\Controllers\OfferController@index')->name('offer-browse');
  Route::get('/user/browse', 'App\Http\Controllers\UserController@index')->name('user-browse');
  Route::get('/wilaya/list', [DatatablesController::class, 'wilayas'])->name('wilaya-list');
  Route::post('/district/list', [DatatablesController::class, 'districts'])->name('district-list');
  Route::get('/supplier/list', [DatatablesController::class, 'suppliers'])->name('supplier-list');
  Route::get('/notice/browse', 'App\Http\Controllers\NoticeController@index')->name('notice-browse');
  Route::get('/driver/browse', 'App\Http\Controllers\DriverController@index')->name('driver-browse');
  Route::get('/ad/browse', 'App\Http\Controllers\AdController@index')->name('ad-browse');
  Route::get('/section/browse', 'App\Http\Controllers\SectionController@index')->name('section-browse');
  Route::get('/family/browse', 'App\Http\Controllers\FamilyController@index')->name('family-browse');
  Route::get('/client/browse', 'App\Http\Controllers\ClientController@index')->name('client-browse');
  // Router for wilayas Controller
  Route::get('/wilaya/browse', [WilayasController::class, 'index'])->name('wilaya-browse');
  // Route for Districts controller
  Route::get('/district/browse', [districtControler::class, 'index'])->name('district-browse');
  // Router for search suppliers ajax
  Route::get('/supplier/browse', [supplierController::class, 'index'])->name('supplier-browse');
  Route::get('/suppliers/search', [supplierController::class , 'search']);
  Route::post('/suppliers/create', [supplierController::class , 'create']);
  Route::post('/suppliers/update', [supplierController::class , 'update']);
  Route::post('/suppliers/delete', [supplierController::class , 'delete']);
  // Router for settings
  Route::get('/settings', [settingController::class, 'index'])->name('settings');

  // Router is hidden
});

Route::group(['middleware' => ['auth']], function () {

  Route::get('/logout','App\Http\Controllers\AuthController@logout');
  Route::post('/user/update','App\Http\Controllers\UserController@update');
  Route::post('/user/change_password','App\Http\Controllers\UserController@change_password');

  Route::post('/category/create','App\Http\Controllers\CategoryController@create');
  Route::post('/category/update','App\Http\Controllers\CategoryController@update');
  Route::post('/category/delete','App\Http\Controllers\CategoryController@delete');
  Route::post('/category/restore','App\Http\Controllers\CategoryController@restore');
  Route::post('/category/get','App\Http\Controllers\CategoryController@get');

  Route::post('/subcategory/create','App\Http\Controllers\SubcategoryController@create');
  Route::post('/subcategory/update','App\Http\Controllers\SubcategoryController@update');
  Route::post('/subcategory/delete','App\Http\Controllers\SubcategoryController@delete');
  Route::post('/subcategory/restore','App\Http\Controllers\SubcategoryController@restore');
  Route::post('/subcategory/get','App\Http\Controllers\SubcategoryController@get');
  Route::post('/subcategory/showCategory','App\Http\Controllers\SubcategoryController@showCategory');


  Route::post('/family/create','App\Http\Controllers\FamilyController@create');
  Route::post('/family/update','App\Http\Controllers\FamilyController@update');
  Route::post('/family/delete','App\Http\Controllers\FamilyController@delete');
  Route::post('/family/restore','App\Http\Controllers\FamilyController@restore');


  Route::post('/product/create','App\Http\Controllers\ProductController@create');
  Route::post('/product/update','App\Http\Controllers\ProductController@update');
  Route::post('/product/delete','App\Http\Controllers\ProductController@delete');
  Route::post('/product/delete-media','App\Http\Controllers\ProductController@deletedMedia');
  Route::post('/product/restore','App\Http\Controllers\ProductController@restore');
  Route::post('/product/get','App\Http\Controllers\ProductController@get');


  Route::post('/discount/create','App\Http\Controllers\DiscountController@create');
  Route::post('/discount/update','App\Http\Controllers\DiscountController@update');
  Route::post('/discount/delete','App\Http\Controllers\DiscountController@delete');
  Route::post('/discount/restore','App\Http\Controllers\DiscountController@restore');


  Route::post('/ad/create','App\Http\Controllers\AdController@create');
  Route::post('/ad/update','App\Http\Controllers\AdController@update');
  Route::post('/ad/delete','App\Http\Controllers\AdController@delete');
  Route::post('/ad/restore','App\Http\Controllers\AdController@restore');


  Route::post('/offer/create','App\Http\Controllers\OfferController@create');
  Route::post('/offer/update','App\Http\Controllers\OfferController@update');
  Route::post('/offer/delete','App\Http\Controllers\OfferController@delete');
  Route::post('/offer/restore','App\Http\Controllers\OfferController@restore');



  Route::post('/section/add','App\Http\Controllers\SectionController@add');
  Route::post('/section/delete','App\Http\Controllers\SectionController@delete');
  Route::post('/section/switch','App\Http\Controllers\SectionController@switch');
  Route::post('/section/insert','App\Http\Controllers\SectionController@insert');


  Route::post('/driver/create','App\Http\Controllers\DriverController@create');
  Route::post('/driver/update','App\Http\Controllers\DriverController@update');
  Route::post('/driver/delete','App\Http\Controllers\DriverController@delete');
  Route::post('/driver/restore','App\Http\Controllers\DriverController@restore');

  Route::post('/item/add','App\Http\Controllers\ItemController@add');
  Route::post('/item/edit','App\Http\Controllers\ItemController@edit');
  Route::post('/item/delete','App\Http\Controllers\ItemController@delete');
  Route::post('/item/restore','App\Http\Controllers\ItemController@restore');

  Route::post('/order/update','App\Http\Controllers\OrderController@update');
  Route::post('/order/delete','App\Http\Controllers\OrderController@delete');
  Route::post('/order/payment-method', [OrderController::class, 'paymentMethod']);

  Route::post('/invoice/update','App\Http\Controllers\InvoiceController@update');

  Route::post('/notice/create','App\Http\Controllers\NoticeController@create');
  Route::post('/notice/update','App\Http\Controllers\NoticeController@update');
  Route::post('/notice/delete','App\Http\Controllers\NoticeController@delete');

  Route::post('/wilaya/create',[WilayasController::class, 'create']);
  Route::post('/wilaya/update',[WilayasController::class, 'update']);
  Route::post('/wilaya/delete',[WilayasController::class, 'delete']);
  Route::get('/wilaya/get',[WilayasController::class, 'get']);

  Route::post('/district/create', [districtControler::class, 'create']);
  Route::post('/district/update', [districtControler::class, 'update']);
  Route::post('/district/delete', [districtControler::class, 'delete']);
  Route::post('/district/get', [districtControler::class, 'get']);


  Route::post('/settings/update',[settingController::class , 'update']);
  Route::get('/setting/get', [settingController::class, 'get']);

  Route::get('/documentation/privacy_policy','App\Http\Controllers\DocumentationController@index')->name('documentation_privacy_policy');
  Route::get('/documentation/about','App\Http\Controllers\DocumentationController@index')->name('documentation_about');

  Route::post('/documentation/update','App\Http\Controllers\DocumentationController@update');

  Route::post('/user/create','App\Http\Controllers\UserController@create');
  Route::post('/user/delete','App\Http\Controllers\UserController@delete');
  Route::post('/user/restore','App\Http\Controllers\UserController@restore');
  Route::post('/user/update','App\Http\Controllers\UserController@update');

  Route::post('/client/create','App\Http\Controllers\ClientController@create');
  Route::post('/client/delete','App\Http\Controllers\ClientController@delete');
  Route::post('/client/restore','App\Http\Controllers\ClientController@restore');
  Route::post('/client/update','App\Http\Controllers\ClientController@update');



  Route::post('/purchase/create','App\Http\Controllers\PurchaseController@create');
  Route::post('/purchase/delete','App\Http\Controllers\PurchaseController@delete');
  Route::post('/purchase/restore','App\Http\Controllers\PurchaseController@restore');
  Route::post('/purchase/update','App\Http\Controllers\PurchaseController@update');
  Route::get('/supplier/{id}/purchases','App\Http\Controllers\PurchaseController@index');
  Route::post('/purchase/list','App\Http\Controllers\DatatablesController@purchases');

  Route::post('/shipping/switch','App\Http\Controllers\SetController@shipping');

  Route::post('invoices/supplier', [InvoiceController::class, 'updateSupplier']);
  // Route::get('invoices/client', [InvoiceController::class, 'updateClient']);

  //language
  Route::get('/lang', function () {
  $locale = session()->get('locale') == 'ar' ? 'en' : 'ar';
  Session::put('locale', $locale);
  App::setLocale(Session::get('locale'));
  return redirect()->back();
});

});


// pages
Route::group(['middleware' => ['auth']], function () {
  Route::get('/pages/account-settings-account', 'App\Http\Controllers\pages\AccountSettingsAccount@index')->name('pages-account-settings-account');
  Route::get('/pages/account-settings-notifications', 'App\Http\Controllers\pages\AccountSettingsNotifications@index')->name('pages-account-settings-notifications');
  Route::get('/pages/account-settings-connections', 'App\Http\Controllers\pages\AccountSettingsConnections@index')->name('pages-account-settings-connections');
  Route::get('/pages/misc-error', 'App\Http\Controllers\pages\MiscError@index')->name('pages-misc-error');
  Route::get('/pages/misc-under-maintenance', 'App\Http\Controllers\pages\MiscUnderMaintenance@index')->name('pages-misc-under-maintenance');
});
// authentication
Route::get('/auth/login-basic', 'App\Http\Controllers\authentications\LoginBasic@index')->name('login');
//Route::get('/auth/register-basic', 'App\Http\Controllers\authentications\RegisterBasic@index')->name('auth-register-basic');
//Route::post('/auth/register-action', 'App\Http\Controllers\authentications\RegisterBasic@register');
Route::post('/auth/login-action', 'App\Http\Controllers\authentications\LoginBasic@login');
Route::get('/auth/forgot-password-basic', 'App\Http\Controllers\authentications\ForgotPasswordBasic@index')->name('auth-reset-password-basic');
Route::get('/auth/logout', 'App\Http\Controllers\authentications\LogoutBasic@logout')->name('auth-logout');





