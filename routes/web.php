<?php

use App\Http\Controllers\LanguageController;
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

/* Route Dashboards */
Route::middleware('auth')->group(function () {
  // Main Page Route
  Route::get('/', 'DashboardController@dashboardAnalytics')->name('dashboard');
  Route::get('/dashboard', 'DashboardController@dashboardAnalytics')->name('dashboard');
  Route::get('analytics', 'DashboardController@dashboardAnalytics')->name('dashboard-analytics');
  Route::get('ecommerce', 'DashboardController@dashboardEcommerce')->name('dashboard-ecommerce');

  /* Route AD Data */
  Route::group(['prefix' => 'ad-data'], function () {
      
      Route::get('users', 'AdDataController@users')->name('ad-data-users');
      Route::get('users-list', 'AdDataController@user_list')->name('user_list');
      Route::get('user/{user}', 'AdDataController@showUser');

      Route::get('computers', 'AdDataController@computers')->name('ad-data-computers');
      Route::get('computers-list', 'AdDataController@computer_list')->name('computer_list');
      Route::get('computer/{user}', 'AdDataController@showComputer');

      Route::get('subnet', 'AdDataController@subnet')->name('ad-data-subnet');
      Route::get('subnet-list', 'AdDataController@subnet_list')->name('computer_list');
      Route::get('subnet/{user}', 'AdDataController@showSubnet');

      Route::get('tree-view', 'AdDataController@tree_view')->name('ad-data-tree-view');
      
      Route::get('groups', 'AdDataController@groups')->name('ad-data-groups');
      
      Route::get('containers', 'AdDataController@containers')->name('ad-data-containers');
      
      Route::get('organizational-units', 'AdDataController@organizational_units')->name('ad-data-organizational-units');
  });
  /* Route AD Data */

  /* Route Policy */
  Route::group(['prefix' => 'policy'], function () {
    Route::get('policies', 'PolicyController@policies')->name('policy-policies');
    Route::get('policy/{user}', 'PolicyController@showPolicy');

    Route::get('reports', 'PolicyController@reports')->name('policy-reports');
    
    Route::get('rules', 'PolicyController@rules')->name('policy-rules');
    
    Route::get('url-lists', 'PolicyController@url_list')->name('policy-url-lists');

    Route::get('block-pages', 'PolicyController@block_pages')->name('policy-block-pages');
    
    Route::get('settings', 'PolicyController@settings')->name('policy-settings');

    Route::get('rule-builder', 'PolicyController@rule_builder');
  });
  /* Route Policy */

  /* Route Proxy */
  Route::group(['prefix' => 'proxy'], function () {
    Route::get('listeners', 'ProxyController@listeners')->name('proxy-listeners');
    
    Route::get('CA', 'ProxyController@ca')->name('proxy-CA');
    
    Route::get('Generate-CA', 'ProxyController@GenerateCA')->name('proxy-Generate-CA');
    
    Route::get('upload-CA', 'ProxyController@upload_ca_page')->name('proxy-upload-CA');
    Route::post('ca/upload', 'ProxyController@upload_ca');
  });
  /* Route Proxy */

  /* Route Network */
  Route::group(['prefix' => 'network'], function () {
    Route::get('interface', 'NetworkController@interface')->name('network-interface');
    
    Route::get('firewall', 'NetworkController@firewall')->name('network-firewall');
  });
  /* Route Network */

  /* Route System */
  Route::group(['prefix' => 'system'], function () {
    Route::get('maintenance', 'SystemController@maintenance')->name('system-maintenance');
    
    Route::get('dashboard', 'DashboardController@dashboardAnalytics')->name('dashboard');

    Route::get('logs', 'SystemController@logs')->name('system-logs');

    Route::get('system-clock', 'SystemController@system_clocks')->name('system-system-clock');
    
    Route::get('LDAP-configurations', 'SystemController@ldap_configurations')->name('system-LDAP-configurations');
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