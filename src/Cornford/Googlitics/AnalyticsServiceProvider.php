<?php namespace Cornford\Googlitics;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class AnalyticsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->loadViewsFrom(base_path('resources/views/cornford/googlitics'), 'googlitics');

		$this->publishes(
			[
				__DIR__ . '/../../config/config.php' => config_path('googlitics.php'),
				__DIR__ . '/../../views' => base_path('resources/views/cornford/googlitics')
			],
			'googlitics'
		);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$configPath = __DIR__ . '/../../config/config.php';
		$this->mergeConfigFrom($configPath, 'googlitics');

		$this->app['analytics'] = $this->app->share(function($app)
		{
			return new Analytics(
				$this->app->make('Illuminate\Foundation\Application'),
				$this->app->view,
				$app['config']->get('googlitics')
			);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('analytics');
	}

}
