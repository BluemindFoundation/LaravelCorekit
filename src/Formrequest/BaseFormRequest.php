<?php

namespace Corekit\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseFormRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        // Extraire les clés de la validation, en tenan  t compte des règles imbriquées
        $allowedFields = collect($this->rules())
            ->keys()
            ->map(fn($key) => explode('.', $key)[0])
            ->unique();

        $incoming = collect($this->all())->keys();

        $extraFields = $incoming->diff($allowedFields);

        if ($extraFields->isNotEmpty()) {
            throw ValidationException::withMessages([
                'forbidden_fields' => ['These fields are not allowed: ' . $extraFields->implode(', ')],
            ]);
        }
    }
}