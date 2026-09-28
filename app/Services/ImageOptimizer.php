<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * Otimiza imagens enviadas pelo painel: redimensiona, converte para WebP
 * e gera uma miniatura. Substitui o Cloudinary previsto no documento de
 * arquitetura, já que tudo roda na hospedagem compartilhada da Hostinger (GD).
 */
class ImageOptimizer
{
    public const MAX_WIDTH = 1600;

    public const THUMB_WIDTH = 640;

    public const QUALITY = 82;

    public static function store(UploadedFile $file, string $directory): string
    {
        $manager = new ImageManager(new Driver);
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'imagem';
        $path = trim($directory, '/').'/'.$name.'-'.Str::lower(Str::random(6)).'.webp';

        $image = $manager->read($file->getRealPath())->scaleDown(width: self::MAX_WIDTH);
        Storage::disk('public')->put($path, (string) $image->toWebp(self::QUALITY));

        $thumb = $manager->read($file->getRealPath())->scaleDown(width: self::THUMB_WIDTH);
        Storage::disk('public')->put(self::thumbPath($path), (string) $thumb->toWebp(self::QUALITY));

        return $path;
    }

    public static function thumbPath(string $path): string
    {
        return preg_replace('/\.webp$/', '', $path).'-thumb.webp';
    }

    public static function delete(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete([$path, self::thumbPath($path)]);
        }
    }
}
