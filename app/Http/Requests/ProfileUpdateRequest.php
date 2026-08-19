<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];

        if ($this->user()->isCandidato()) {
            $rules = array_merge($rules, [
                'anos_experiencia' => ['nullable', 'integer', 'min:0', 'max:60'],
                'localizacao' => ['nullable', 'string', 'max:255'],
                'formacao' => ['nullable', 'string', 'max:255'],
                'disponibilidade' => ['nullable', 'string', Rule::in([
                    'imediata', 'a_combinar', 'part_time', 'full_time',
                ])],
                'bio' => ['nullable', 'string', 'max:1000'],
                'curriculo' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
                'skills' => ['nullable', 'array'],
                'skills.*' => ['integer', 'exists:skills,id'],
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'curriculo.mimes' => 'O currículo deve ser um ficheiro PDF.',
            'curriculo.max' => 'O currículo não pode ultrapassar 5MB.',
            'anos_experiencia.integer' => 'Indique um número de anos válido.',
            'anos_experiencia.max' => 'O número de anos de experiência parece inválido.',
        ];
    }
}
