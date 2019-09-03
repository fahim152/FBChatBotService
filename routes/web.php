<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/register', 'HomeController@index')->name('home');
Route::post('/lang', 'HomeController@lang')->name('lang');
 /* -------------------------------------------------------------------------
    | Bot
    |------------------------------------------------------------------------ */
    Route::get('/bot','BotController@bot');
    Route::post('/bot','BotController@bot');

Route::group(['middleware' => 'auth'],function() {

    /* -------------------------------------------------------------------------
    | Admin Dashboard
    |------------------------------------------------------------------------ */
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
    Route::get('/denied', 'HomeController@denied')->name('denied');

    /* -------------------------------------------------------------------------
    | User
    |------------------------------------------------------------------------ */
    Route::get('/users', 'UserController@index')->name('users');
    Route::post('/users/dataload', 'UserController@datatable')->name('users_load');
    Route::get('/users/create', 'UserController@create')->name('user_create');
    Route::get('/users/edit/{user}', 'UserController@edit')->name('user_edit');
    Route::post('/users/update', 'UserController@update')->name('user_update');

    Route::get('/users/group/{user_group}', 'UserController@index')->name('users_group');
    Route::post('/users/group/{user_group}/dataload', 'UserController@datatable')->name('users_group_load');

    Route::get('/users/permission/{user}', 'UserController@permission')->name('user_permission');
    Route::post('/users/permission/update', 'UserController@permissionUpdate')->name('user_delete');

    Route::get('/profile', 'UserController@profile')->name('profile');
    Route::post('/profile/update', 'UserController@profileUpdate')->name('profile_update');
    Route::post('/profile/password/update', 'UserController@passwordUpdate')->name('password_update');
    Route::post('/profile/picture/update', 'UserController@pictureUpdate')->name('picture_update');
    Route::post('/profile/signature/update', 'UserController@signatureUpdate')->name('signature_update');

    /* -------------------------------------------------------------------------
    | Group
    |------------------------------------------------------------------------ */
    Route::get('/groups', 'GroupController@index')->name('groups');
    Route::post('/groups/dataload', 'GroupController@datatable')->name('groups_load');
    Route::get('/groups/create', 'GroupController@create')->name('group_create');
    Route::get('/groups/edit/{group}', 'GroupController@edit')->name('group_edit');
    Route::post('/groups/update', 'GroupController@update')->name('group_update');
    Route::get('/groups/permission/{group}', 'GroupController@createPermission')->name('group_permission');
    Route::post('/groups/permission/update', 'GroupController@updatePermission')->name('group_permission_update');

    /* -------------------------------------------------------------------------
    | Setting
    |------------------------------------------------------------------------ */
    Route::get('/settings', 'SettingController@index')->name('settings');
    Route::post('/settings/dataload', 'SettingController@datatable')->name('settings_load');
    Route::get('/settings/create', 'SettingController@create')->name('settings_create');
    Route::get('/settings/edit/{setting}', 'SettingController@edit')->name('settings_edit');
    Route::post('/settings/update', 'SettingController@update')->name('settings_update');

    /* -------------------------------------------------------------------------
    | Setting
    |------------------------------------------------------------------------ */
    Route::get('/chats', 'ChatController@index')->name('chats');
    Route::post('/chats/dataload', 'ChatController@datatable')->name('chats_load');
    Route::get('/chats/create', 'ChatController@create')->name('chats_create');
    Route::get('/chats/edit/{setting}', 'ChatController@edit')->name('chats_edit');
    Route::post('/chats/update', 'ChatController@update')->name('chats_update');




});
