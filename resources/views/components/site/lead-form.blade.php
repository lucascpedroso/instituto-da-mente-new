@props([
    'type' => 'contato',            // contato | agendamento | inscricao_curso
    'therapyId' => null,
    'courseId' => null,
    'professionalId' => null,
    'therapies' => null,            // coleções opcionais: quando informadas, o visitante escolhe o assunto
    'courses' => null,
    'professionals' => null,
    'chooseType' => false,          // página de contato: visitante escolhe o tipo
    'submitLabel' => null,
    'id' => 'formulario',
])
@php
    $selectedType = old('type', $type);
    $submitLabel ??= match ($type) {
        'inscricao_curso' => 'Quero me inscrever',
        'agendamento' => 'Solicitar agendamento',
        default => 'Enviar mensagem',
    };
@endphp
@if (config('instituto.static_preview'))
    <x-site.preview-form-notice :id="$id" />
@else
<form method="POST" action="{{ route('leads.store') }}" id="{{ $id }}" novalidate
      x-data="{ type: @js($selectedType), sending: false }" @submit="sending = true"
      class="space-y-5">
    @csrf
    <x-honeypot />
    <input type="hidden" name="_anchor" value="{{ $id }}">

    @if ($chooseType)
        <fieldset>
            <legend class="field-label">Como podemos ajudar?</legend>
            <div class="grid gap-2 sm:grid-cols-3">
                @foreach (['agendamento' => 'Agendar uma sessão', 'inscricao_curso' => 'Cursos e formação', 'contato' => 'Outro assunto'] as $value => $label)
                    <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-areia bg-white px-4 py-3 text-sm has-[:checked]:border-marrom has-[:checked]:bg-offwhite has-[:checked]:font-semibold has-[:checked]:text-marrom">
                        <input type="radio" name="type" value="{{ $value }}" x-model="type" class="accent-marrom" @checked($selectedType === $value)>
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </fieldset>
    @else
        <input type="hidden" name="type" value="{{ $type }}">
    @endif

    @if ($therapyId)
        <input type="hidden" name="therapy_id" value="{{ $therapyId }}">
    @endif
    @if ($courseId)
        <input type="hidden" name="course_id" value="{{ $courseId }}">
    @endif
    @if ($professionalId)
        <input type="hidden" name="professional_id" value="{{ $professionalId }}">
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="{{ $id }}-name" class="field-label">Nome <span class="text-terracota">*</span></label>
            <input id="{{ $id }}-name" name="name" type="text" required autocomplete="name" value="{{ old('name') }}" class="field" @error('name') aria-invalid="true" aria-describedby="{{ $id }}-name-error" @enderror>
            @error('name') <p id="{{ $id }}-name-error" class="field-error">{{ $message }}</p> @enderror
        </div>

        <div x-data="phoneMask">
            <label for="{{ $id }}-phone" class="field-label">WhatsApp / telefone <span class="text-terracota">*</span></label>
            <input id="{{ $id }}-phone" name="phone" type="tel" required inputmode="tel" autocomplete="tel-national" placeholder="(19) 99999-9999"
                   value="{{ old('phone') }}" @input="format($event)" class="field" @error('phone') aria-invalid="true" aria-describedby="{{ $id }}-phone-error" @enderror>
            @error('phone') <p id="{{ $id }}-phone-error" class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="{{ $id }}-email" class="field-label">E-mail <span class="font-normal text-cinza/90">(opcional)</span></label>
            <input id="{{ $id }}-email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" class="field" @error('email') aria-invalid="true" @enderror>
            @error('email') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        @if ($therapies && $therapies->isNotEmpty())
            <div class="sm:col-span-2" x-show="type === 'agendamento'" x-cloak>
                <label for="{{ $id }}-therapy" class="field-label">Tipo de atendimento</label>
                <select id="{{ $id }}-therapy" name="therapy_id" class="field" :disabled="type !== 'agendamento'">
                    <option value="">Ainda não sei / quero orientação</option>
                    @foreach ($therapies as $therapy)
                        <option value="{{ $therapy->id }}" @selected(old('therapy_id') == $therapy->id)>{{ $therapy->title }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if ($professionals && $professionals->count() > 1)
            <div class="sm:col-span-2" x-show="type === 'agendamento'" x-cloak>
                <label for="{{ $id }}-professional" class="field-label">Profissional de preferência</label>
                <select id="{{ $id }}-professional" name="professional_id" class="field" :disabled="type !== 'agendamento'">
                    <option value="">Sem preferência</option>
                    @foreach ($professionals as $professional)
                        <option value="{{ $professional->id }}" @selected(old('professional_id') == $professional->id)>{{ $professional->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if ($courses && $courses->isNotEmpty())
            <div class="sm:col-span-2" x-show="type === 'inscricao_curso'" x-cloak>
                <label for="{{ $id }}-course" class="field-label">Curso de interesse</label>
                <select id="{{ $id }}-course" name="course_id" class="field" :disabled="type !== 'inscricao_curso'">
                    <option value="">Quero conhecer as opções</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if ($type === 'agendamento' || $chooseType)
            <fieldset class="sm:col-span-2" x-show="type === 'agendamento'" @if ($chooseType) x-cloak @endif>
                <legend class="field-label">Modalidade de preferência</legend>
                <div class="flex flex-wrap gap-2">
                    @foreach (\App\Models\Lead::MODALITIES as $value => $label)
                        <label class="flex cursor-pointer items-center gap-2 rounded-full border border-areia bg-white px-4 py-2 text-sm has-[:checked]:border-marrom has-[:checked]:bg-offwhite has-[:checked]:text-marrom">
                            <input type="radio" name="modality" value="{{ $value }}" class="accent-marrom" @checked(old('modality', 'indiferente') === $value) :disabled="type !== 'agendamento'">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </fieldset>
        @endif

        <div class="sm:col-span-2">
            <label for="{{ $id }}-message" class="field-label">Mensagem <span class="font-normal text-cinza/90">(opcional)</span></label>
            <textarea id="{{ $id }}-message" name="message" rows="4" maxlength="2000" class="field"
                      placeholder="Conte brevemente como podemos ajudar. Não é necessário detalhar questões de saúde por aqui.">{{ old('message') }}</textarea>
            @error('message') <p class="field-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="flex items-start gap-3 text-sm leading-relaxed">
            <input type="checkbox" name="consent" value="1" required class="mt-1 size-4 shrink-0 accent-marrom" @checked(old('consent'))>
            <span>Concordo com o uso dos meus dados para que o Instituto da Mente entre em contato, conforme a
                <a href="{{ route('privacy') }}" target="_blank" class="font-medium text-laranja-escuro underline">Política de Privacidade</a>.</span>
        </label>
        @error('consent') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <button type="submit" class="btn-primary w-full sm:w-auto" :disabled="sending">
        <span x-show="!sending">{{ $submitLabel }}</span>
        <span x-show="sending" x-cloak>Enviando…</span>
    </button>
</form>
@endif
