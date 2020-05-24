<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'Controller@index');

Route::group(['middleware' => ['web']], function () {
    Route::get('donate', 'DonateController@index');
    Route::post('donate', 'DonateController@submit');
    Route::get('success', 'DonateController@success');
    Route::get('error', 'DonateController@error');
});
