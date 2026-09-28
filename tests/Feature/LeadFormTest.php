<?php

use App\Mail\NewLeadMail;
use App\Models\Course;
use App\Models\Lead;
use Database\Seeders\ContentSeeder;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->seed(ContentSeeder::class);
    config(['honeypot.enabled' => false]);
});

function validLead(array $overrides = []): array
{
    return array_merge([
        'type' => 'contato',
        'name' => 'Maria da Silva',
        'phone' => '(19) 99123-4567',
        'email' => 'maria@example.com',
        'message' => 'Gostaria de informações.',
        'consent' => '1',
    ], $overrides);
}

it('stores a lead, notifies the institute and redirects to the thank-you page', function () {
    Mail::fake();
    $course = Course::first();

    $this->post('/contato', validLead(['type' => 'inscricao_curso', 'course_id' => $course->id]))
        ->assertRedirect(route('leads.thanks'));

    $lead = Lead::sole();
    expect($lead->phone)->toBe('19991234567')
        ->and($lead->course_id)->toBe($course->id)
        ->and($lead->status)->toBe('novo')
        ->and($lead->consent_at)->not->toBeNull()
        ->and($lead->ip_hash)->toHaveLength(64);

    Mail::assertSent(NewLeadMail::class, fn ($mail) => $mail->hasTo(config('instituto.leads_to')) && $mail->lead->is($lead));

    $this->followRedirects($this->post('/contato', validLead()))->assertSee('Recebemos sua mensagem');
});

it('attaches campaign attribution captured on the landing page', function () {
    Mail::fake();

    $this->get('/?utm_source=google&utm_medium=cpc&utm_campaign=formacao&gclid=abc123')->assertOk();
    $this->get('/contato');
    $this->post('/contato', validLead());

    expect(Lead::sole())
        ->utm_source->toBe('google')
        ->utm_medium->toBe('cpc')
        ->utm_campaign->toBe('formacao')
        ->gclid->toBe('abc123');
});

it('requires name, phone and LGPD consent', function () {
    $this->from('/contato')
        ->post('/contato', ['type' => 'contato', '_anchor' => 'agendar-form'])
        ->assertRedirect('/contato#agendar-form')
        ->assertSessionHasErrors(['name', 'phone', 'consent']);

    expect(Lead::count())->toBe(0);
});

it('rejects invalid phone numbers and lead types', function () {
    $this->post('/contato', validLead(['phone' => '1234']))->assertSessionHasErrors('phone');
    $this->post('/contato', validLead(['type' => 'hack']))->assertSessionHasErrors('type');
});

it('never loses a lead when the email fails', function () {
    Mail::shouldReceive('to')->andThrow(new RuntimeException('SMTP fora do ar'));

    $this->post('/contato', validLead())->assertRedirect(route('leads.thanks'));

    expect(Lead::count())->toBe(1);
});

it('does not show the thank-you page without a submission', function () {
    $this->get('/obrigado')->assertRedirect('/');
});

it('blocks bots with the honeypot', function () {
    config(['honeypot.enabled' => true]);

    $this->post('/contato', validLead());

    expect(Lead::count())->toBe(0);
});

it('rate limits form submissions', function () {
    Mail::fake();

    foreach (range(1, 5) as $i) {
        $this->post('/contato', validLead())->assertRedirect();
    }

    $this->post('/contato', validLead())->assertTooManyRequests();
});
