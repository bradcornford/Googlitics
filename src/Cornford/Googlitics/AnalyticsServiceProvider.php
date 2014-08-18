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
		$this->package('cornford/googlitics');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['analytics'] = $this->app->share(function($app)
		{
			$config = $app['config']->get('googlitics::config');

			return new Analytics(
				$this->app->make('Illuminate\Foundation\Application'),
				$this->app->view,
				$config
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
