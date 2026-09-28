<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Course;
use App\Models\Post;
use App\Models\Professional;
use App\Models\Setting;
use App\Models\Therapy;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('pt_BR');

        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        // Formulários públicos: até 5 envios por minuto por IP.
        RateLimiter::for('forms', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));

        // Sitemap é regenerado quando o conteúdo muda.
        foreach ([Post::class, Book::class, Course::class, Therapy::class, Professional::class, Category::class] as $model) {
            $model::saved(fn () => Cache::forget('sitemap.xml'));
            $model::deleted(fn () => Cache::forget('sitemap.xml'));
        }

        View::composer('components.layouts.site', function ($view) {
            $view->with([
                'settings' => Setting::values(),
                'navTherapies' => Therapy::active()->get(['title', 'slug']),
                'navCourses' => Course::published()->get(['title', 'slug', 'is_flagship']),
            ]);
        });
    }
}
