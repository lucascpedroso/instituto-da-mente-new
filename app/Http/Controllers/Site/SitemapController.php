<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Course;
use App\Models\Post;
use App\Models\Professional;
use App\Models\Therapy;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $xml = Cache::remember('sitemap.xml', now()->addHour(), function () {
            $sitemap = Sitemap::create();

            foreach (['home', 'about', 'therapies.index', 'courses.index', 'professionals.index', 'books.index', 'blog.index', 'testimonials.index', 'faq', 'contact', 'privacy', 'terms'] as $route) {
                $sitemap->add(Url::create(route($route))->setPriority($route === 'home' ? 1.0 : 0.7));
            }

            Therapy::active()->get()->each(fn ($m) => $sitemap->add(Url::create(route('therapies.show', $m))->setLastModificationDate($m->updated_at)->setPriority(0.8)));
            Course::published()->get()->each(fn ($m) => $sitemap->add(Url::create(route('courses.show', $m))->setLastModificationDate($m->updated_at)->setPriority(0.9)));
            Professional::active()->get()->each(fn ($m) => $sitemap->add(Url::create(route('professionals.show', $m))->setLastModificationDate($m->updated_at)));
            Book::visible()->get()->each(fn ($m) => $sitemap->add(Url::create(route('books.show', $m))->setLastModificationDate($m->updated_at)));
            Post::published()->get()->each(fn ($m) => $sitemap->add(Url::create(route('blog.show', $m))->setLastModificationDate($m->updated_at)));
            Category::whereHas('posts', fn ($q) => $q->published())->get()->each(fn ($m) => $sitemap->add(Url::create(route('blog.category', $m))->setPriority(0.5)));

            return $sitemap->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots()
    {
        $lines = app()->isProduction()
            ? ['User-agent: *', 'Disallow: /admin', 'Disallow: /obrigado', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /']; // ambientes de teste/homologação não são indexados

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
