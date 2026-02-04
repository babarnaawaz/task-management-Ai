<?php

namespace App\Providers;

use App\Models\Task;
use App\Observers\TaskObserver;
use App\Services\AnthropicService;
use App\Services\SubtaskService;
use App\Services\TaskService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('task.manager', function ($app) {
            return new TaskService(
                $app->make(SubtaskService::class)
            );
        });

        $this->app->singleton(AnthropicService::class, function ($app) {
            return new AnthropicService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Task::observe(TaskObserver::class);
    }
}
