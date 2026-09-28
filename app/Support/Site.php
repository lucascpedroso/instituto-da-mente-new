<?php

namespace App\Support;

use App\Models\Faq;
use App\Models\Setting;

/**
 * Utilidades compartilhadas pelas páginas públicas (WhatsApp, dados estruturados).
 */
class Site
{
    public static function whatsappUrl(?string $message = null, ?string $number = null): string
    {
        $number = preg_replace('/\D/', '', $number ?: Setting::get('whatsapp'));
        $message ??= Setting::get('whatsapp_message');

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }

    public static function instagramUrl(): string
    {
        return 'https://www.instagram.com/'.ltrim((string) Setting::get('instagram'), '@').'/';
    }

    public static function fullAddress(): string
    {
        return collect([Setting::get('address'), Setting::get('district'), Setting::get('city')])->filter()->implode(' – ');
    }

    /** Dados estruturados da clínica/instituto, presentes em todas as páginas. */
    public static function organizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => ['MedicalClinic', 'EducationalOrganization'],
            '@id' => url('/').'#organizacao',
            'name' => 'Instituto da Mente',
            'slogan' => 'Terapia para toda a família',
            'url' => url('/'),
            'logo' => asset('images/logo-laranja.png'),
            'image' => asset('images/logo-laranja.png'),
            'telephone' => '+'.preg_replace('/\D/', '', Setting::get('whatsapp')),
            'email' => Setting::get('email'),
            'foundingDate' => '2023-11',
            'founder' => ['@type' => 'Person', 'name' => 'Ricardo Mello'],
            'taxID' => Setting::get('cnpj'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => Setting::get('address'),
                'addressLocality' => 'Campinas',
                'addressRegion' => 'SP',
                'postalCode' => Setting::get('postal_code') ?: null,
                'addressCountry' => 'BR',
            ],
            'sameAs' => [self::instagramUrl()],
            'medicalSpecialty' => 'Psychiatric',
            'availableService' => ['Psicanálise', 'Terapia de casal', 'Terapia familiar'],
        ];
    }

    /** @param  array<string, string|null>  $items  rótulo => url */
    public static function breadcrumbSchema(array $items): array
    {
        $position = 0;

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect(['Início' => url('/')] + $items)->map(fn ($url, $name) => array_filter([
                '@type' => 'ListItem',
                'position' => ++$position,
                'name' => $name,
                'item' => $url,
            ]))->values()->all(),
        ];
    }

    /** @param  iterable<Faq>  $faqs */
    public static function faqSchema(iterable $faqs): array
    {
        $items = collect($faqs);

        if ($items->isEmpty()) {
            return [];
        }

        return [[
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $items->map(fn ($f) => [
                '@type' => 'Question',
                'name' => $f->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer],
            ])->values()->all(),
        ]];
    }

    /** Serializa um bloco JSON-LD removendo valores vazios em qualquer nível. */
    public static function jsonLd(array $data): string
    {
        $clean = function (array $a) use (&$clean) {
            foreach ($a as $k => $v) {
                if (is_array($v)) {
                    $a[$k] = $v = $clean($v);
                }
                if ($v === null || $v === '' || $v === []) {
                    unset($a[$k]);
                }
            }

            return array_is_list($a) ? array_values($a) : $a;
        };

        return json_encode($clean($data), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);
    }
}
