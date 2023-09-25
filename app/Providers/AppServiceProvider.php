<?php

/**
 * This file is part of the zidane-blog package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Providers;

use App\Exceptions\Renderers\JsonExceptionRenderer;
use App\Repository\EntryRepository;
use App\Repository\EntryRepositoryInterface;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryInterface;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthServiceInterface;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;

use function request;
use function resolve;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(EntryRepositoryInterface::class, EntryRepository::class);

        // Services
        $this->app->bind(AuthServiceInterface::class, AuthService::class);

        // Exception
        if (request()->expectsJson() || request()->wantsJson() || request()->acceptsJson()) {
            $this->registerExceptionHandler();
        }

        //Telescope
        if ($this->app->environment('local')) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    protected function registerExceptionHandler(): void
    {
        $exceptionHandler = resolve(ExceptionHandler::class);

        $exceptionHandler->renderable(function (\Exception $exception, $request) {
            return $this->app->make(JsonExceptionRenderer::class)->render($exception, $request);
        });
    }
}
