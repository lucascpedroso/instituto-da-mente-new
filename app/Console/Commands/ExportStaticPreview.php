<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Category;
use App\Models\Course;
use App\Models\Post;
use App\Models\Professional;
use App\Models\Therapy;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

/**
 * Gera uma pré-visualização estática do site (HTML puro) para o GitHub Pages.
 * Formulários viram botões de WhatsApp e o painel /admin não é exportado.
 * O site real continua sendo publicado na Hostinger (deploy.sh).
 */
class ExportStaticPreview extends Command
{
    protected $signature = 'app:export-static
        {--url= : URL pública da pré-visualização, ex.: https://usuario.github.io/repositorio}
        {--out=dist : Pasta de saída}';

    protected $description = 'Exporta as páginas públicas como HTML estático (pré-visualização no GitHub Pages)';

    public function handle(Kernel $kernel): int
    {
        $url = rtrim((string) $this->option('url'), '/');

        if (! $url) {
            $this->error('Informe --url (ex.: https://usuario.github.io/repositorio).');

            return self::FAILURE;
        }

        $out = base_path($this->option('out'));
        File::deleteDirectory($out);
        File::ensureDirectoryExists($out);

        config([
            'instituto.static_preview' => true,
            'app.url' => $url,
            'filesystems.disks.public.url' => $url.'/storage',
        ]);
        Storage::forgetDisk('public');
        URL::forceRootUrl($url);
        URL::useAssetOrigin($url);
        if (str_starts_with($url, 'https://')) {
            URL::forceScheme('https');
        }

        $failed = 0;

        foreach ($this->paths() as $path) {
            $response = $kernel->handle($request = Request::create($path));
            $kernel->terminate($request, $response);

            if ($response->getStatusCode() !== 200) {
                $this->error("{$response->getStatusCode()}  {$path}");
                $failed++;

                continue;
            }

            $file = $path === '/' ? 'index.html' : trim($path, '/').'/index.html';
            File::ensureDirectoryExists(dirname("{$out}/{$file}"));
            File::put("{$out}/{$file}", $response->getContent());
            $this->line("200  {$path}");
        }

        // Página 404 do GitHub Pages
        $response = $kernel->handle(Request::create('/pagina-nao-encontrada-preview'));
        File::put("{$out}/404.html", $response->getContent());

        File::put("{$out}/robots.txt", "User-agent: *\nDisallow: /\n");
        File::put("{$out}/.nojekyll", '');

        foreach (['build', 'images'] as $dir) {
            File::copyDirectory(public_path($dir), "{$out}/{$dir}");
        }
        foreach (['favicon.ico', 'favicon-32.png', 'apple-touch-icon.png'] as $file) {
            File::copy(public_path($file), "{$out}/{$file}");
        }
        if (File::isDirectory(storage_path('app/public'))) {
            File::copyDirectory(storage_path('app/public'), "{$out}/storage");
        }

        $this->info("Pré-visualização gerada em {$out}");

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    /** @return list<string> */
    private function paths(): array
    {
        return array_merge(
            ['/', '/sobre', '/terapias', '/formacao', '/profissionais', '/livros', '/blog', '/depoimentos', '/duvidas', '/contato', '/politica-de-privacidade', '/termos-de-uso'],
            Therapy::active()->pluck('slug')->map(fn ($s) => "/terapias/{$s}")->all(),
            Course::published()->pluck('slug')->map(fn ($s) => "/formacao/{$s}")->all(),
            Professional::active()->pluck('slug')->map(fn ($s) => "/profissionais/{$s}")->all(),
            Book::visible()->pluck('slug')->map(fn ($s) => "/livros/{$s}")->all(),
            Post::published()->pluck('slug')->map(fn ($s) => "/blog/{$s}")->all(),
            Category::whereHas('posts', fn ($q) => $q->published())->pluck('slug')->map(fn ($s) => "/blog/categoria/{$s}")->all(),
        );
    }
}
