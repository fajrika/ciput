<?php

Route::group(['middleware' => 'web', 'prefix' => 'kontraktor', 'namespace' => 'Modules\Kontraktor\Http\Controllers'], function()
{
    Route::get('/', 'KontraktorController@index');
    Route::get('/tender/detail','KontraktorController@show');
    Route::get('/tender','KontraktorController@tender');
    Route::get('/tender/add-penawaran','KontraktorController@tenderadd');
});
