<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeadRequest;
use App\Mail\NewLeadMail;
use App\Models\Lead;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LeadController extends Controller
{
    public function store(LeadRequest $request)
    {
        $data = $request->safe()->except('consent');

        // 1) O lead é sempre salvo primeiro — nenhum contato se perde.
        $lead = Lead::create($data + $request->session()->get('attribution', []) + [
            'source_url' => mb_substr((string) $request->headers->get('referer', $request->session()->get('landing_url')), 0, 500),
            'consent_at' => now(),
            'ip_hash' => hash('sha256', $request->ip().config('app.key')),
        ]);

        // 2) Depois, a notificação por e-mail. Uma falha no SMTP não impede o envio do formulário.
        try {
            Mail::to(config('instituto.leads_to'))->send(new NewLeadMail($lead));
        } catch (\Throwable $e) {
            Log::error('Falha ao enviar e-mail de novo lead', ['lead_id' => $lead->id, 'erro' => $e->getMessage()]);
        }

        return redirect()->route('leads.thanks')->with('lead_type', $lead->type);
    }

    public function thanks()
    {
        // Página de conversão: só é exibida logo após um envio real.
        if (! session()->has('lead_type')) {
            return redirect()->route('home');
        }

        return view('site.thanks', ['type' => session('lead_type')]);
    }
}
