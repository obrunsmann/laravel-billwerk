<?php

namespace Lefamed\LaravelBillwerk;

use Illuminate\Support\ServiceProvider;

class LaravelBillwerkServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->loadMigrationsFrom(__DIR__ . 'resources/migrations');
		$this->publishes([
			__DIR__ . '/resources/config/laravel-billwerk.php' => config_path('laravel-billwerk.php')
		]);
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}
