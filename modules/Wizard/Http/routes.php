<?php

Route::group(['prefix' => 'wizard', 'namespace' => 'Modules\Wizard\Http\Controllers'], function()
{
	Route::get('/example', 'WizardController@index');
});
