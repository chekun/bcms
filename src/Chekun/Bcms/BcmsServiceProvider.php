<?php namespace Chekun\Bcms;

use Illuminate\Support\ServiceProvider;
use Chekun\Bcms\Connector\BcmsConnector;

class QueueServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBcmsConnector($this->app['queue']);
    }

    /**
     * Register the Bcms queue connector.
     *
     * @param  \Illuminate\Queue\QueueManager  $manager
     * @return void
     */
    protected function registerBcmsConnector($manager)
    {
        $manager->addConnector('bcms', function()
        {
            return new BcmsConnector;
        });
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