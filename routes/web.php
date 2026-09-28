<?php

use App\Http\Controllers\Site\BlogController;
use App\Http\Controllers\Site\BookController;
use App\Http\Controllers\Site\CourseController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\LeadController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\ProfessionalController;
use App\Http\Controllers\Site\SitemapController;
use App\Http\Controllers\Site\TestimonialController;
use App\Http\Controllers\Site\TherapyController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::get('/', HomeController::class)->name('home');

Route::get('/sobre', [PageController::class, 'about'])->name('about');
Route::get('/duvidas', [PageController::class, 'faq'])->name('faq');
Route::get('/contato', [PageController::class, 'contact'])->name('contact');
Route::get('/politica-de-privacidade', [PageController::class, 'privacy'])->name('privacy');
Route::get('/termos-de-uso', [PageController::class, 'terms'])->name('terms');

Route::get('/terapias', [TherapyController::class, 'index'])->name('therapies.index');
Route::get('/terapias/{therapy:slug}', [TherapyController::class, 'show'])->name('therapies.show');

Route::get('/formacao', [CourseController::class, 'index'])->name('courses.index');
Route::get('/formacao/{course:slug}', [CourseController::class, 'show'])->name('courses.show');

Route::get('/profissionais', [ProfessionalController::class, 'index'])->name('professionals.index');
Route::get('/profissionais/{professional:slug}', [ProfessionalController::class, 'show'])->name('professionals.show');

Route::get('/livros', [BookController::class, 'index'])->name('books.index');
Route::get('/livros/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/categoria/{category:slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/depoimentos', [TestimonialController::class, 'index'])->name('testimonials.index');

// Formulários: honeypot + limite de envios por minuto
Route::middleware([ProtectAgainstSpam::class, 'throttle:forms'])->group(function () {
    Route::post('/contato', [LeadController::class, 'store'])->name('leads.store');
    Route::post('/depoimentos', [TestimonialController::class, 'store'])->name('testimonials.store');
});
Route::get('/obrigado', [LeadController::class, 'thanks'])->name('leads.thanks');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
