<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Request;
use App\Models\Comment;
use App\Observers\CommentObserver;
use App\Models\ComplaintAction;
use App\Observers\ComplaintActionObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrapFive();

        Gate::define('isManager', function ($user) {
            return $user && $user->isManager();
        });

        Activity::saving(function (Activity $activity) {
            $ip = Request::ip();
            $activity->properties = $activity->properties->merge(['ip_address' => $ip]);
        });

        // Register observers
        Comment::observe(CommentObserver::class);
        ComplaintAction::observe(ComplaintActionObserver::class);
    }
}
