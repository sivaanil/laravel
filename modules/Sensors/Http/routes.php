<?php

Route::group(['prefix' => 'sensors', 'namespace' => 'Modules\Sensors\Http\Controllers'], function()
{
	Route::get('/', 'SensorsController@index');
    Route::get('grid/contactClosure/{id}', 'SensorsController@ccGridData');
    Route::get('grid/analogSensor', 'SensorsController@analogSensorGridData');
    Route::get('grid/relay', 'SensorsController@relayGridData');

    Route::get('CC/detail/{id}', 'SensorsController@getContactClosure');
    Route::post('CC/save', 'SensorsController@saveContactClosure');
});
