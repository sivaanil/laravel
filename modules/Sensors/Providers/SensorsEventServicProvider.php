<?php namespace Modules\Sensors\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

class SensorsEventServicProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;


	/**
    	* The event listener mappings for the application.
     	*
     	* @var array
     	*/
    	protected $listen = [
        	'Modules\Sensors\Events\SomeEvent' => [
            	'App\Listeners\EventListener',
        	],
    	];


	/**
	 * Boot the application events.
	 * 
	 * @return void
	 */
	public function boot()
	{
		$this->registerConfig();
		$this->registerViews();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{		
	
	}

	/**
	 * Register config.
	 * 
	 * @return void
	 */
	protected function registerConfig()
	{
		$this->publishes([
		    __DIR__.'/../Config/config.php' => config_path('sensors.php'),
		]);
		$this->mergeConfigFrom(
		    __DIR__.'/../Config/config.php', 'sensors'
		);
	}

	/**
	 * Register views.
	 * 
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = base_path('resources/views/modules/sensors');

		$sourcePath = __DIR__.'/../Resources/views';

		$this->publishes([
			$sourcePath => $viewPath
		]);

		$this->loadViewsFrom(array_merge(array_map(function ($path) {
			return $path . '/modules/sensors';
		}, \Config::get('view.paths')), [$sourcePath]), 'sensors');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
