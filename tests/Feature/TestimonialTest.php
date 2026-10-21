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

it('returns to the form on the page, with errors or the confirmation', function () {
    $valid = ['name' => 'A. S.', 'kind' => 'paciente', 'content' => 'Fui muito bem acolhida desde a primeira sessão.', 'consent' => '1'];

    $this->from('/depoimentos')
        ->post('/depoimentos', ['content' => 'Curto'] + $valid)
        ->assertRedirect('/depoimentos#enviar-depoimento')
        ->assertSessionHasErrors('content')
        ->assertSessionHasInput('name', 'A. S.');

    $this->from('/depoimentos?page=2')
        ->post('/depoimentos', $valid)
        ->assertRedirect('/depoimentos?page=2#enviar-depoimento');

    $this->get('/depoimentos')->assertSee('id="enviar-depoimento"', false)->assertSee('Obrigado pelo seu depoimento!');
});
