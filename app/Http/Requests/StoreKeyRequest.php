<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKeyRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('nome') && ! $this->has('name')) {
            $data['name'] = $this->input('nome');
        }

        if ($this->has('descricao') && ! $this->has('place')) {
            $data['place'] = $this->input('descricao');
        }

        if ($this->has('status')) {
            $data['status'] = match ($this->input('status')) {
                'disponivel' => 'Disponível',
                'emprestada' => 'Emprestada',
                default => $this->input('status'),
            };
        }

        $this->merge($data);
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80', Rule::unique('keys', 'name')],
            'place' => ['required', 'string', 'max:80'],
            'status' => ['sometimes', 'string', Rule::in(['Disponível', 'Emprestada'])],
        ];
    }
}
