<?php

namespace PatricPoba\MtnMomo;

use PatricPoba\MtnMomo\MtnCollection;
use PatricPoba\MtnMomo\MtnRemittance;
use Illuminate\Support\ServiceProvider;
use PatricPoba\MtnMomo\MtnDisbursement;

class MtnMomoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->registerResources();

        // $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/mtn-momo.php', 'mtn-momo');

         
        $config = new MtnConfig([
            // general credentials
            'baseUrl'                   => config('mtn-momo.base_url'),
            'currency'                  => config('mtn-momo.currency'),
            'targetEnvironment'         => config('mtn-momo.environment'),
            // collection credentials
            'collectionApiSecret'       => config('mtn-momo.collection.api_secret'),
            'collectionPrimaryKey'      => config('mtn-momo.collection.primary_key'),
            'collectionUserId'          => config('mtn-momo.collection.user_id'),
            'collectionCallbackUrl'     => config('mtn-momo.collection.callback_url'),
            // disbursement credentials
            'disbursementApiSecret'     => config('mtn-momo.disbursement.api_secret'),
            'disbursementPrimaryKey'    => config('mtn-momo.disbursement.primary_key'),
            'disbursementUserId'        => config('mtn-momo.disbursement.user_id'),
            'disbursementCallbackUrl'   => config('mtn-momo.disbursement.callback_url'),
            // remittance credentials
            'remittanceApiSecret'       => config('mtn-momo.remittance.api_secret'),
            'remittancePrimaryKey'      => config('mtn-momo.remittance.primary_key'),
            'remittanceUserId'          => config('mtn-momo.remittance.user_id'),
            'remittanceCallbackUrl'     => config('mtn-momo.remittance.callback_url'),
        ]);

         
        $this->app->singleton('mtn-momo-collection', function () use ($config) { 
            return new MtnCollection($config);
        });

        
        $this->app->singleton('mtn-momo-disbursement', function () use ($config) { 
            return new MtnDisbursement($config);
        });

        
        $this->app->singleton('mtn-momo-remittance', function () use ($config) {
            return new MtnRemittance($config);
        });
    }


    // protected function registerRoutes()
    // {
    //     // $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

    //     \Route::group([
    //         'as'        => 'qh-support-ticket-system.',
    //         'namespace' => 'Qodehub\TicketingApp\Http\Controllers',
    //     ], function () {
    //         $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    //     });
    // }

    // protected function registerResources()
    // {
    //     // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'reports');
        
    //     $this->loadViewsFrom(__DIR__.'/../resources/views', 'qh-tickets');
        
    //     // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    // }
 
        

    protected function registerPublishing()
    {
        // Automatically apply the package configuration
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('mtn-momo.php'),
        ], 'config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/reports'),
        ], 'views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/reports'),
        ], 'assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/reports'),
        ], 'lang');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
