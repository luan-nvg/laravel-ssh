<?php namespace	MNP\Tunneler;

use Illuminate\Support\ServiceProvider;
use MNP\Tunneler\Create;


class TunnelerServiceProvider extends ServiceProvider{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Default path to configuration
     * @var string
     */
    protected $configPath = __DIR__ . '/../config/tunneler.php';


    public function boot()
    {
        // helps deal with Lumen vs Laravel differences
        if (function_exists('config_path')) {
            $publishPath = config_path('tunneler.php');
        } else {
            $publishPath = base_path('config/tunneler.php');
        }

        $this->publishes([$this->configPath => $publishPath], 'config');

        if (config('tunneler.on_boot')){
            dispatch(new Create());
        }
    }

    public function register()
    {
        if ( is_a($this->app,'Laravel\Lumen\Application')){
            $this->app->configure('tunneler');
        }
        $this->mergeConfigFrom($this->configPath, 'tunneler');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['command.tunneler.activate'];
    }

}
