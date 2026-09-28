<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Faq;
use App\Models\Testimonial;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::published()->get();

        return view('site.courses.index', [
            'flagship' => $courses->firstWhere('is_flagship', true),
            'courses' => $courses->where('is_flagship', false),
            'faqs' => Faq::active()->where('group', 'formacao')->get(),
        ]);
    }

    public function show(Course $course)
    {
        abort_unless($course->status === 'publicado', 404);

        return view('site.courses.show', [
            'course' => $course,
            'others' => Course::published()->whereKeyNot($course->id)->take(3)->get(),
            'testimonials' => Testimonial::approved()->where('kind', 'aluno')->take(3)->get(),
            'faqs' => Faq::active()->where('group', 'formacao')->get(),
        ]);
    }
}
