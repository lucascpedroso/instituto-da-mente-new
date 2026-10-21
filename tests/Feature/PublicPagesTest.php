<?php

use App\Models\Book;
use App\Models\Course;
use App\Models\Post;
use App\Models\Professional;
use App\Models\Therapy;
use Database\Seeders\ContentSeeder;

beforeEach(fn () => $this->seed(ContentSeeder::class));

it('renders every public page', function (string $url) {
    $this->get($url)->assertOk();
})->with([
    '/', '/sobre', '/duvidas', '/contato', '/politica-de-privacidade', '/termos-de-uso',
    '/terapias', '/terapias/psicanalise-individual',
    '/formacao', '/formacao/formacao-em-psicanalise-clinica',
    '/profissionais', '/profissionais/ricardo-mello',
    '/livros', '/livros/psicanalise',
    '/blog', '/blog?busca=sessão', '/blog/categoria/psicanalise', '/blog/a-terapia-e-para-sempre',
    '/depoimentos',
]);

it('uses portuguese and core SEO tags', function () {
    $this->get('/')
        ->assertSee('<html lang="pt-BR">', false)
        ->assertSee('<link rel="canonical"', false)
        ->assertSee('"@type":["MedicalClinic","EducationalOrganization"]', false)
        ->assertSee('FAQPage', false)
        ->assertSee('Terapia para');
});

it('hides unpublished content', function () {
    Course::where('slug', 'formacao-em-hipnose')->update(['status' => 'rascunho']);
    Therapy::where('slug', 'hipnose')->update(['is_active' => false]);
    Book::where('slug', 'psicanalise')->update(['status' => 'rascunho']);
    Post::where('slug', 'a-terapia-e-para-sempre')->update(['status' => 'rascunho']);

    $this->get('/formacao/formacao-em-hipnose')->assertNotFound();
    $this->get('/terapias/hipnose')->assertNotFound();
    $this->get('/livros/psicanalise')->assertNotFound();
    $this->get('/blog/a-terapia-e-para-sempre')->assertNotFound();
    $this->get('/formacao')->assertDontSee('Formação em Hipnose');
});

it('publishes scheduled posts only when their date arrives', function () {
    $post = Post::where('slug', 'a-terapia-e-para-sempre')->first();
    $post->update(['status' => 'agendado', 'published_at' => now()->addDay()]);

    $this->get('/blog/a-terapia-e-para-sempre')->assertNotFound();
    $this->get('/blog')->assertDontSee($post->title);

    $this->travel(2)->days();

    $this->get('/blog/a-terapia-e-para-sempre')->assertOk();
});

it('shows sold out books with a notice', function () {
    Book::where('slug', 'psicanalise')->update(['status' => 'esgotado']);

    $this->get('/livros/psicanalise')->assertOk()->assertSee('Esgotado no momento');
});

it('returns a portuguese 404 page', function () {
    $this->get('/pagina-que-nao-existe')->assertNotFound()->assertSee('Esta página não foi encontrada');
});

it('lists published content in the sitemap', function () {
    Post::where('slug', 'a-terapia-e-para-sempre')->update(['status' => 'rascunho']);

    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
        ->assertSee(url('/formacao/formacao-em-psicanalise-clinica'), false)
        ->assertSee(url('/terapias/psicanalise-individual'), false)
        ->assertDontSee(url('/blog/a-terapia-e-para-sempre'), false);
});

it('blocks indexing outside production', function () {
    $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /');
});

it('renders the mobile menu outside the sticky header', function () {
    // Dentro do <header> (backdrop-blur + z-40), o menu fixo ficava cortado na altura do cabeçalho.
    $html = $this->get('/')->assertOk()->getContent();
    $menu = strpos($html, 'id="menu-mobile"');

    expect($menu)->not->toBeFalse()
        ->and(strrpos(substr($html, 0, $menu), '<template x-teleport="body">'))->not->toBeFalse()
        ->and($html)->toContain('x-modal="open"');
});

it('lists professionals in a carousel with a profile lightbox for each one', function () {
    Professional::create(['name' => 'Ana Ribeiro', 'profession' => 'Psicóloga', 'short_bio' => 'Atende crianças.', 'bio' => '<p>Bio completa da Ana.</p>', 'whatsapp' => '5519988887777']);
    Professional::create(['name' => 'Oculto Silva', 'profession' => 'Psicólogo', 'is_active' => false]);

    $this->get('/profissionais')->assertOk()
        ->assertSee('aria-label="Profissionais do Instituto"', false)
        ->assertSee('data-professional="ricardo-mello"', false)
        ->assertSee('data-profile="ana-ribeiro"', false)
        ->assertSee('Bio completa da Ana.', false)
        ->assertSee('Agendar com Ana')
        ->assertSee('https://wa.me/5519988887777', false)
        ->assertSee('/profissionais/ana-ribeiro#agendar', false)
        ->assertDontSee('Oculto Silva');
});

it('shows no watermark logo in the call-to-action band', function () {
    $html = $this->get('/')->getContent();
    $cta = substr($html, strpos($html, 'Dê o primeiro passo') - 400, 400);

    expect($cta)->not->toContain('logo-branco.png');
});
