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
		$this->loadMigrationsFrom(__DIR__.'/resources/migrations');
		$this->loadRoutesFrom(__DIR__.'/routes.php');
		$this->loadTranslationsFrom(__DIR__.'/resources/lang/', 'ld-billwerk');
		$this->loadViewsFrom(__DIR__.'/resources/views', 'ld-billwerk');
		$this->publishes([
			__DIR__.'/resources/config/laravel-billwerk.php' => config_path('laravel-billwerk.php'),
			__DIR__.'/resources/views' => resource_path('views/vendor/ld-billwerk'),
			__DIR__.'/resources/assets' => resources_path('assets/vendor/laravel-billwerk'),
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
