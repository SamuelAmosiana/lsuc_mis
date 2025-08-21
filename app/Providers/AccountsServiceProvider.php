<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Blade;

class AccountsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share common data with all accounts views
        View::composer('accounts.*', function ($view) {
            // Add the accounts JavaScript file
            $view->with([
                'accountsScripts' => true,
            ]);
        });

        // Add a directive to include the accounts scripts
        Blade::directive('accountsScripts', function () {
            return '<?php if(isset($accountsScripts)): ?>'.
                   '<script src="' . asset('js/accounts/fees.js') . '" defer></script>'.
                   '<?php endif; ?>';
        });
    }
}
