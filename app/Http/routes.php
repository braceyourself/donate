<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'Controller@index');

Route::group(['middleware' => ['web']], function () {
    Route::get('donate', 'DonateController@index');
    Route::post('donate', 'DonateController@submit');
});
