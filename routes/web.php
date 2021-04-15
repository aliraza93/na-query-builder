<?php

use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Ad Data Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\AD\Ad_userController;
use App\Http\Controllers\AD\AD_ComputersController;
use App\Http\Controllers\AdDataController;
use App\Http\Controllers\AD\AD_GroupsController;
use App\Http\Controllers\AD\AD_ContainerController;
use App\Http\Controllers\AD\AD_OusController;
use App\Http\Controllers\AD\NamedPageController;
use App\Http\Controllers\AD\AD_TrafficController;
use App\Http\Controllers\AD\SyncAllController;

/*
|--------------------------------------------------------------------------
| CRM Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Crm\PolicyController;
use App\Http\Controllers\Crm\PolicyrulesController;

/*
|--------------------------------------------------------------------------
| Proxy Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\ProxyController;

/*
|--------------------------------------------------------------------------
| Network Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\NetworkController;

/*
|--------------------------------------------------------------------------
| System Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\SystemController;

/*
|--------------------------------------------------------------------------
| Dashboard Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\DashboardController;

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

Auth::routes();

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
  Route::get('fetch', [SyncAllController::class, 'index']);
  /*
  |--------------------------------------------------------------------------
  | Main Routes
  |--------------------------------------------------------------------------
  */
  Route::get('/', [DashboardController::class,'dashboardAnalytics'])->name('dashboard');
  Route::get('/dashboard', [DashboardController::class,'dashboardAnalytics'])->name('dashboard');
  Route::get('analytics', [DashboardController::class,'dashboardAnalytics'])->name('dashboard-analytics');
  Route::get('ecommerce', [DashboardController::class,'dashboardEcommerce'])->name('dashboard-ecommerce');

  /*
  |--------------------------------------------------------------------------
  | Ad Data Routes
  |--------------------------------------------------------------------------
  */
  Route::group(['prefix' => 'ad-data'], function () {
      
    Route::get('users', [Ad_userController::class, 'users'])->name('ad-data-users');
    Route::get('users-list', [Ad_userController::class,'user_list'])->name('user_list');
    Route::get('user/{user}', [Ad_userController::class,'showUser']);

    Route::get('computers', [AD_ComputersController::class,'computers'])->name('ad-data-computers');
    Route::get('computers-list', [AD_ComputersController::class,'computer_list'])->name('computer_list');
    Route::get('computer/{user}', 'AdDataController@showComputer');

    Route::get('subnet', [AdDataController::class,'subnet'])->name('ad-data-subnet');
    Route::get('subnet-list', [AdDataController::class,'subnet_list'])->name('computer_list');
    Route::get('subnet/{user}', [AdDataController::class,'showSubnet']);

    Route::get('tree-view', [AdDataController::class,'tree_view'])->name('ad-data-tree-view');
    
    Route::get('groups', [AD_GroupsController::class,'groups'])->name('ad-data-groups');
    Route::get('groups-list', [AD_GroupsController::class,'groups_list'])->name('groups_list');

    Route::get('containers', [AD_ContainerController::class,'containers'])->name('ad-data-containers');
    Route::get('containers-list', [AD_ContainerController::class,'containers_list'])->name('containers_list');

    Route::get('organizational-units', [AD_OusController::class,'organizational_units'])->name('ad-data-organizational-units');
    Route::get('ou-list', [AD_OusController::class,'ou_list'])->name('ou_list');
  });
  /*
  |--------------------------------------------------------------------------
  | End Ad Data Routes
  |--------------------------------------------------------------------------
  */

  /*
  |--------------------------------------------------------------------------
  | Policy Routes
  |--------------------------------------------------------------------------
  */
  Route::group(['prefix' => 'policy'], function () {
    Route::get('policies', [App\Http\Controllers\AD\PolicyController::class,'policies'])->name('policy-policies');
    Route::get('policies-list', [App\Http\Controllers\AD\PolicyController::class,'policies_list'])->name('policies_list');
    Route::get('policy/{user}', 'PolicyController@showPolicy');
    Route::post('add-policy', [App\Http\Controllers\AD\PolicyController::class, 'store'])->name('block-page.store');
    Route::get('policy/{policy}/edit', [App\Http\Controllers\AD\PolicyController::class, 'edit']);
    Route::post('policy/{policy}/update', [App\Http\Controllers\AD\PolicyController::class, 'update']);
    Route::delete('delete-policy/{policy}', [App\Http\Controllers\AD\PolicyController::class, 'destroy']);
    Route::post('change-priority/{policy}/{action}', [App\Http\Controllers\AD\PolicyController::class, 'change_priority']);

    Route::get('reports', 'PolicyController@reports')->name('policy-reports');
    
    Route::get('rules', [PolicyrulesController::class,'rules'])->name('policy-rules');
    Route::get('rules-list', [PolicyrulesController::class,'rules_list'])->name('rules_list');

    Route::get('url-lists', [App\Http\Controllers\PolicyController::class,'url_list'])->name('policy-url-lists');

    Route::get('block-pages', [NamedPageController::class,'block_pages'])->name('policy-block-pages');
    Route::get('block-pages-list', [NamedPageController::class,'block_pages_list'])->name('block-pages_list');
    Route::post('block-page', [NamedPageController::class, 'store'])->name('block-page.store');
    Route::delete('block-page/{page}', [NamedPageController::class, 'destroy']);
    Route::get('block-page/{page}/edit', [NamedPageController::class, 'edit']);
    Route::post('block-page/{page}/update', [NamedPageController::class, 'update']);
    Route::get('get-block-pages', [NamedPageController::class, 'index']);

    Route::get('settings', [PolicyController::class,'settings'])->name('policy-settings');

    Route::get('rule-builder', [PolicyrulesController::class,'rule_builder']);
  });
  /*
  |--------------------------------------------------------------------------
  | Policy Routes
  |--------------------------------------------------------------------------
  */

  /* Route Proxy */
  Route::group(['prefix' => 'proxy'], function () {
    Route::get('listeners', [ProxyController::class,'listeners'])->name('proxy-listeners');
    
    Route::get('CA', [ProxyController::class,'ca'])->name('proxy-CA');
 
    Route::get('Generate-CA', [ProxyController::class,'GenerateCA'])->name('proxy-Generate-CA');
 
    Route::get('upload-CA', [ProxyController::class,'upload_ca_page'])->name('proxy-upload-CA');
    Route::post('ca/upload', [ProxyController::class,'upload_ca']);
  });
  /* Route Proxy */

  /* Route Network */
  Route::group(['prefix' => 'network'], function () {
    Route::get('interface', [NetworkController::class,'interface'])->name('network-interface');
    
    Route::get('firewall', [NetworkController::class,'firewall'])->name('network-firewall');
  });
  /* Route Network */

  /* Route System */
  Route::group(['prefix' => 'system'], function () {
    Route::get('maintenance', [SystemController::class,'maintenance'])->name('system-maintenance');
    
    Route::get('dashboard', [DashboardController::class,'dashboardAnalytics'])->name('dashboard');

    Route::get('logs', [AD_TrafficController::class,'logs'])->name('system-logs');
    Route::get('logs-list', [AD_TrafficController::class, 'traffic_logs_list'])->name('system-logs-list');
    Route::get('system-clock', [SystemController::class,'system_clocks'])->name('system-system-clock');
    
    Route::get('LDAP-configurations', [SystemController::class,'ldap_configurations'])->name('system-LDAP-configurations');
  });
  /* Route System */

  /* Route System */
  Route::group(['prefix' => 'query-builder'], function () {
    Route::get('operators', 'QueryBuilderController@operators')->name('query-builder-operators');
    Route::get('operators-list', 'OperatorsController@operators_list');
    Route::post('operators', 'OperatorsController@store');

    Route::get('triggers', 'QueryBuilderController@triggers')->name('query-builder-triggers');
    // Route::get('operators-list', 'OperatorsController@operators_list');
    // Route::post('operators', 'OperatorsController@store');
  
    Route::get('operands', 'QueryBuilderController@operands')->name('query-builder-operands');
  });
  /* Route System */

});