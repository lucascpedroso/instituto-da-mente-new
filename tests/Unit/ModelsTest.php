<?php

use App\Models\Lead;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Therapy;
use App\Services\ImageOptimizer;
use App\Support\Site;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

it('generates unique slugs from titles', function () {
    $a = Therapy::create(['title' => 'Terapia de Casal', 'summary' => 'x']);
    $b = Therapy::create(['title' => 'Terapia de Casal', 'summary' => 'y']);

    expect($a->slug)->toBe('terapia-de-casal')->and($b->slug)->toBe('terapia-de-casal-2');
});

it('sets the publication date when a post is published', function () {
    $post = Post::create(['title' => 'Novo post', 'body' => '<p>Texto</p>', 'status' => 'publicado']);

    expect($post->published_at)->not->toBeNull()->and($post->readingTime())->toBe(1);
});

it('falls back to default settings and saves changes', function () {
    expect(Setting::get('whatsapp'))->toBe('5519991607338');

    Setting::put(['whatsapp' => '5519900000000']);

    expect(Setting::get('whatsapp'))->toBe('5519900000000')
        ->and(Site::whatsappUrl('Olá'))->toBe('https://wa.me/5519900000000?text=Ol%C3%A1');
});

it('formats brazilian phone numbers', function () {
    expect((new Lead(['phone' => '19991234567']))->formattedPhone())->toBe('(19) 99123-4567')
        ->and((new Lead(['phone' => '1932345678']))->formattedPhone())->toBe('(19) 3234-5678');
});

it('prunes leads older than two years (LGPD)', function () {
    $old = Lead::create(['type' => 'contato', 'name' => 'Antigo', 'phone' => '19991234567', 'consent_at' => now()]);
    $old->forceFill(['updated_at' => now()->subYears(3)])->saveQuietly();
    Lead::create(['type' => 'contato', 'name' => 'Recente', 'phone' => '19991234567', 'consent_at' => now()]);

    $this->artisan('model:prune', ['--model' => Lead::class]);

    expect(Lead::pluck('name')->all())->toBe(['Recente']);
});

it('converts uploads to webp with a thumbnail and cleans up replaced images', function () {
    Storage::fake('public');

    $path = ImageOptimizer::store(UploadedFile::fake()->image('Foto Ricardo.jpg', 2400, 3000), 'profissionais');

    expect($path)->toStartWith('profissionais/foto-ricardo-')->toEndWith('.webp');
    Storage::disk('public')->assertExists([$path, ImageOptimizer::thumbPath($path)]);
    expect(getimagesizefromstring(Storage::disk('public')->get($path))[0])->toBe(ImageOptimizer::MAX_WIDTH);

    $therapy = Therapy::create(['title' => 'Com imagem', 'summary' => 'x', 'image' => $path]);
    $therapy->update(['image' => null]);

    Storage::disk('public')->assertMissing([$path, ImageOptimizer::thumbPath($path)]);
});

it('creates database backups and keeps only the most recent', function () {
    $db = tempnam(sys_get_temp_dir(), 'idm');
    config(['database.connections.backup_test' => ['driver' => 'sqlite', 'database' => $db, 'prefix' => '']]);

    $dir = sys_get_temp_dir().'/idm-backups-'.uniqid();
    config(['instituto.backup_path' => $dir]);
    File::ensureDirectoryExists($dir);
    File::put($dir.'/backup-2000-01-01-000000.sql.gz', 'antigo');

    $this->artisan('app:backup-database', ['--keep' => 1, '--connection' => 'backup_test'])->assertSuccessful();

    expect(File::glob($dir.'/backup-*.sql.gz'))->toHaveCount(1)
        ->and(File::exists($dir.'/backup-2000-01-01-000000.sql.gz'))->toBeFalse();

    @unlink($db);
    File::deleteDirectory($dir);
});
