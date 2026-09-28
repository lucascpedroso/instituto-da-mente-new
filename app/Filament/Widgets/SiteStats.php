<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Leads\LeadResource;
use App\Filament\Resources\Testimonials\TestimonialResource;
use App\Models\Lead;
use App\Models\Post;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $month = Lead::where('created_at', '>=', now()->startOfMonth());
        $fromAds = (clone $month)->where(fn ($q) => $q->whereNotNull('gclid')->orWhereNotNull('fbclid')->orWhereNotNull('utm_source'))->count();

        $stats = [
            Stat::make('Contatos novos', Lead::where('status', 'novo')->count())
                ->description('Aguardando retorno')
                ->color('danger')
                ->url(auth()->user()->isAdmin() ? LeadResource::getUrl('index') : null),
            Stat::make('Contatos no mês', (clone $month)->count())
                ->description($fromAds.' vindos de anúncios/campanhas'),
            Stat::make('Inscrições em cursos (mês)', (clone $month)->where('type', 'inscricao_curso')->count())
                ->description('Agendamentos no mês: '.(clone $month)->where('type', 'agendamento')->count()),
            Stat::make('Depoimentos pendentes', Testimonial::where('status', 'pendente')->count())
                ->description('Posts publicados: '.Post::published()->count())
                ->color('warning')
                ->url(auth()->user()->isAdmin() ? TestimonialResource::getUrl('index') : null),
        ];

        return $stats;
    }

    public static function canView(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }
}
