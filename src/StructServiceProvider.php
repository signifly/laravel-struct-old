<?php

namespace Signifly\Struct;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Signifly\Struct\Exceptions\ErrorHandlerInterface;
use Signifly\Struct\Exceptions\Handler;
use Signifly\Struct\Http\Controllers\WebhookController;
use Signifly\Struct\Webhooks\SecretProvider;
use Signifly\Struct\Webhooks\Webhook;

class StructServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishConfig();
        $this->registerMacros();
    }

    public function register()
    {
        $this->app->singleton(Struct::class, fn () => Factory::fromConfig());

        $this->app->alias(Struct::class, 'struct');

        $this->app->bind(ErrorHandlerInterface::class, Handler::class);

        $this->app->singleton(SecretProvider::class, function (Application $app) {
            $secretProvider = config('struct.webhooks.secret_provider');

            return $app->make($secretProvider);
        });
    }

    protected function publishConfig()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/struct.php' => config_path('struct.php'),
            ], 'laravel-struct');
        }

        $this->mergeConfigFrom(__DIR__.'/../config/struct.php', 'struct');
    }

    protected function registerMacros(): void
    {
        Route::macro('structWebhooks', function (string $uri = 'struct/webhooks') {
            return $this->post($uri, [WebhookController::class, 'handle'])->name('struct.webhooks');
        });

        Request::macro('structEventKey', fn() => $this->header(Webhook::HEADER_EVENT_KEY));

    }
}
