<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('firebase', function () {
            // Directly use the storage path for the credentials file
            return (new Factory)
                ->withServiceAccount(storage_path('firebase/firebase_credentials.json')) // Use the correct path
                ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // No additional actions required in boot for now
    }
}
