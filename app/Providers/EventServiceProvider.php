<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        /* Here will be a routine that collects all event observers from core and
        3rd party plugins and binds to the observers using the following way:
        UPDATE: get listeners list from Balcon service
        */
        /** @var \App\Core\Balcon $balcon */
        $balcon = $this->app->make('Balcon');

        $this->listen = $balcon->getExtensionsContainer()->getEventListeners();

        parent::boot($events);

    }
}
