<?php

namespace App\Providers;

use App\Services\NotificationService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Kirame\PayMongo\PayMongo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PayMongo::class, function ($app) {
            $secret = config('paymongo.secret_key');

            if (empty($secret)) {
                $secret = $this->loadSecretFromEnvFile();
            }

            $baseUrl = config('paymongo.base_url', 'https://api.paymongo.com/v1');
            $timeout = config('paymongo.timeout', 15);
            $retries = config('paymongo.retries', 2);

            if (! is_string($secret) || $secret === '') {
                throw new \RuntimeException('PAYMONGO_SECRET_KEY is not set. Ensure .env contains PAYMONGO_SECRET_KEY and clear config cache.');
            }

            return new PayMongo(
                secretKey: $secret,
                baseUrl: $baseUrl,
                timeout: $timeout,
                retries: $retries,
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $userId = auth()->id();
            $service = app(NotificationService::class);

            $view->with([
                'notificationsUnreadCount' => $service->unreadCountForUser($userId),
                'notificationsDropdown'    => $service->recentForUser($userId, null, 5),
            ]);
        });
    }

    protected function loadSecretFromEnvFile(): ?string
    {
        $path = base_path('.env');

        if (! file_exists($path)) {
            return null;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (! str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $value = trim($value, "\"'");

            if ($key === 'PAYMONGO_SECRET_KEY') {
                return $value;
            }
        }

        return null;
    }
}
