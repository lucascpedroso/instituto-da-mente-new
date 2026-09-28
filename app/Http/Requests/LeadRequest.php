<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => preg_replace('/\D/', '', (string) $this->input('phone')),
        ]);
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(array_keys(Lead::TYPES))],
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['nullable', 'email:rfc', 'max:160'],
            'phone' => ['required', 'digits_between:10,11'],
            'message' => ['nullable', 'string', 'max:2000'],
            'modality' => ['nullable', Rule::in(array_keys(Lead::MODALITIES))],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'therapy_id' => ['nullable', 'integer', 'exists:therapies,id'],
            'professional_id' => ['nullable', 'integer', 'exists:professionals,id'],
            'consent' => ['accepted'],
        ];
    }

    /** Em caso de erro, volta direto para o formulário na página. */
    protected function getRedirectUrl(): string
    {
        $anchor = preg_replace('/[^a-z0-9-]/', '', (string) $this->input('_anchor')) ?: 'formulario';

        return strtok(url()->previous(), '#').'#'.$anchor;
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'email' => 'e-mail',
            'phone' => 'telefone/WhatsApp',
            'message' => 'mensagem',
            'modality' => 'modalidade',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.digits_between' => 'Informe um telefone com DDD, por exemplo (19) 99999-9999.',
            'consent.accepted' => 'Para continuar, é preciso concordar com a Política de Privacidade.',
        ];
    }
}
