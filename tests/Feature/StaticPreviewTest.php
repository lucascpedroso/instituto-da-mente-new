<?php

use Database\Seeders\ContentSeeder;
use Illuminate\Support\Facades\File;

it('exports a static preview without forms or admin', function () {
    $this->seed(ContentSeeder::class);
    $out = 'storage/framework/testing/preview-'.uniqid();

    $this->artisan('app:export-static', ['--url' => 'https://exemplo.github.io/site', '--out' => $out])->assertSuccessful();

    $home = File::get(base_path("{$out}/index.html"));
    expect($home)->toContain('Pré-visualização')
        ->toContain('noindex, nofollow')
        ->toContain('href="https://exemplo.github.io/site/sobre"');

    $contact = File::get(base_path("{$out}/contato/index.html"));
    expect($contact)->not->toContain('<form method="POST"')->toContain('Conversar no WhatsApp');

    expect(File::exists(base_path("{$out}/formacao/formacao-em-psicanalise-clinica/index.html")))->toBeTrue()
        ->and(File::exists(base_path("{$out}/404.html")))->toBeTrue()
        ->and(File::exists(base_path("{$out}/admin")))->toBeFalse()
        ->and(File::get(base_path("{$out}/robots.txt")))->toContain('Disallow: /');

    File::deleteDirectory(base_path($out));
});
