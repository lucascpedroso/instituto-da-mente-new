<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Course;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Professional;
use App\Models\Testimonial;
use App\Models\Therapy;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('site.home', [
            'therapies' => Therapy::active()->take(8)->get(),
            'flagship' => Course::published()->where('is_flagship', true)->first(),
            'courses' => Course::published()->where('is_flagship', false)->take(5)->get(),
            'founder' => Professional::active()->where('is_featured', true)->first(),
            'testimonials' => Testimonial::approved()->take(3)->get(),
            'book' => Book::visible()->where('is_featured', true)->first() ?? Book::visible()->first(),
            'posts' => Post::published()->with('category')->take(3)->get(),
            'faqs' => Faq::active()->where('show_on_home', true)->take(5)->get(),
        ]);
    }
}
