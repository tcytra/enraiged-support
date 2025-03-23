<?php

namespace Enraiged;

use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    /**
     *  Register any application services.
     *
     *  @return void
     */
    public function register()
    {
        $this->commands([
            Commands\CleanStorage::class,
            Commands\CleanTemp::class,
        ]);
    }
}
