<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Configurações editáveis pelo painel (contato, redes, pixels).
 */
class Setting extends Model
{
    public const CACHE_KEY = 'site-settings';

    /** Valores padrão (dados reais do Instituto) usados quando nada foi salvo. */
    public const DEFAULTS = [
        'whatsapp' => '5519991607338',
        'phone' => '(19) 99160-7338',
        'email' => 'contato@institutodamente.com.br',
        'address' => 'Rua Camargo Pimentel, 392/394',
        'district' => 'Jardim Guanabara',
        'city' => 'Campinas/SP',
        'postal_code' => '',
        'hours' => 'Segunda a sexta, com horários agendados',
        'instagram' => 'instituto_da_mente',
        'cnpj' => '15.596.749/0001-10',
        'maps_query' => 'Rua Camargo Pimentel, 392, Jardim Guanabara, Campinas - SP',
        'whatsapp_message' => 'Olá! Vim pelo site do Instituto da Mente e gostaria de mais informações.',
        'meta_pixel_id' => '',
        'ga4_id' => '',
        'google_ads_id' => '',
        'google_ads_conversion_label' => '',
    ];

    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    public static function values(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => array_merge(
            self::DEFAULTS,
            array_filter(static::query()->pluck('value', 'key')->all(), fn ($v) => $v !== null),
        ));
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::values()[$key] ?? $default;
    }

    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget(self::CACHE_KEY);
    }
}
