<?php

Route::group(['prefix' => 'snmpforward', 'namespace' => 'Modules\Snmpforward\Http\Controllers'], function()
{
    Route::get('grid', 'SNMPForwardController@gridData');
	Route::get('/', 'SNMPForwardController@index');
    // To delete an SNMP Destination
    Route::get('delete/{id}', 'SNMPForwardController@delete');
    // To add or update an SNMP Destination
    Route::any('save', 'SNMPForwardController@save');
    // To retrieve details about a specific SNMPDestination
    Route::get('detail/{id}', 'SNMPForwardController@getDestination');
});
