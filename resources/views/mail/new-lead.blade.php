<x-mail::message>
# Novo contato pelo site

**Tipo:** {{ $lead->typeLabel() }}
@if ($lead->interest())
**Interesse:** {{ $lead->interest() }}
@endif

**Nome:** {{ $lead->name }}
**Telefone/WhatsApp:** {{ $lead->formattedPhone() }}
@if ($lead->email)
**E-mail:** {{ $lead->email }}
@endif
@if ($lead->modality)
**Modalidade:** {{ \App\Models\Lead::MODALITIES[$lead->modality] ?? $lead->modality }}
@endif

@if ($lead->message)
**Mensagem:**
{{ $lead->message }}
@endif

@if ($lead->utm_source || $lead->gclid || $lead->fbclid)
**Origem:** {{ collect([$lead->utm_source, $lead->utm_medium, $lead->utm_campaign])->filter()->implode(' / ') ?: ($lead->gclid ? 'Google Ads' : 'Meta Ads') }}
@endif

<x-mail::button :url="'https://wa.me/55'.$lead->phone">
Responder no WhatsApp
</x-mail::button>

<x-mail::button :url="\App\Filament\Resources\Leads\LeadResource::getUrl('edit', ['record' => $lead])" color="success">
Abrir no painel
</x-mail::button>

Recebido em {{ $lead->created_at->format('d/m/Y \à\s H:i') }}.
</x-mail::message>
