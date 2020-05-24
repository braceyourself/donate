<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'Controller@index');

Route::group(['middleware' => ['web']], function () {
    Route::post('donate', 'DonateController@submit');
    Route::get('donate', 'DonateController@index');

    Route::get('success', 'Controller@success');
    Route::get('error', 'Controller@error');
});
