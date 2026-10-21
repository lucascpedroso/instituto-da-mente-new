<?php

namespace Database\Seeders;

use App\Models\Professional;
use Illuminate\Database\Seeder;

/**
 * Dados fictícios usados SOMENTE pelos testes de frontend (Playwright, banco database/e2e.sqlite).
 * O conteúdo real tem um só profissional; o carrossel e a navegação do lightbox precisam de vários.
 */
class E2eSeeder extends Seeder
{
    public function run(): void
    {
        $people = [
            ['Ana Teste Ribeiro', 'Psicóloga clínica', 'CRP 06/000001', ['Crianças e adolescentes', 'Orientação de pais', 'Ansiedade']],
            ['Bruno Teste Carvalho', 'Psicanalista', null, ['Terapia de casal', 'Luto', 'Saúde mental do homem']],
            ['Carla Teste Mendes', 'Psicóloga · Terapeuta familiar', 'CRP 06/000002', ['Terapia familiar', 'Idosos', 'Depressão', 'Traumas']],
            ['Diego Teste Lima', 'Psicanalista clínico', null, ['Atendimento LGBTQIAP+', 'Ansiedade']],
        ];

        foreach ($people as $i => [$name, $profession, $registration, $specialties]) {
            Professional::updateOrCreate(['name' => $name], [
                'profession' => $profession,
                'registration' => $registration,
                'short_bio' => "Perfil fictício de teste. {$name} atende presencialmente em Campinas e online, com escuta acolhedora e sigilosa.",
                'bio' => '<p>Perfil fictício criado para os testes automatizados do site.</p><p>'.str_repeat('Texto de apresentação para verificar a rolagem do perfil. ', 12).'</p>',
                'education' => '<ul><li>Formação em Psicanálise Clínica</li><li>Especialização fictícia</li></ul>',
                'specialties' => $specialties,
                'is_active' => true,
                'sort' => $i + 1,
            ]);
        }
    }
}
