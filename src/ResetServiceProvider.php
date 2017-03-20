<?php

namespace Beeldvoerders\Reset;

use Illuminate\Support\ServiceProvider;

class ResetServiceProvider extends ServiceProvider
{
	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			realpath(__DIR__.'/../config/reset.php') => config_path('reset.php')
		]);

		$this->mergeConfigFrom(
			realpath(__DIR__.'/../config/reset.php'), 'reset'
		);

		$this->setupCommands();
	}

	/**
	 * Setup the news commands
	 *
	 * @return void
	 */
	private function setupCommands()
	{
		$kernel = $this->app->make('Illuminate\Contracts\Console\Kernel');

		$kernel->addCommand( Console\Commands\CreateCommand::class );
		$kernel->addCommand( Console\Commands\ResetCommand::class );
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}