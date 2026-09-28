<?php

use App\Models\Testimonial;

beforeEach(fn () => config(['honeypot.enabled' => false]));

it('stores submitted testimonials as pending until approved', function () {
    $this->post('/depoimentos', [
        'name' => 'A. S.',
        'kind' => 'paciente',
        'content' => 'Fui muito bem acolhida e o processo mudou minha vida.',
        'consent' => '1',
    ])->assertSessionHas('testimonial_sent');

    $testimonial = Testimonial::sole();
    expect($testimonial->status)->toBe('pendente');

    $this->get('/depoimentos')->assertDontSee('mudou minha vida');

    $testimonial->update(['status' => 'aprovado']);

    $this->get('/depoimentos')->assertSee('mudou minha vida');
    $this->get('/')->assertSee('mudou minha vida');
});

it('requires consent to publish a testimonial', function () {
    $this->post('/depoimentos', ['name' => 'A. S.', 'kind' => 'aluno', 'content' => str_repeat('Excelente formação. ', 3)])
        ->assertSessionHasErrors('consent');

    expect(Testimonial::count())->toBe(0);
});
