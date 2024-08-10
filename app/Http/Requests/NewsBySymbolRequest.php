<?php

namespace App\Http\Requests;

use App\Dto\NewsBySymbolDto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class NewsBySymbolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'symbol' => 'required|string|uppercase',
        ];
    }

    public function messages(): array
    {
        return [
            'symbol.required' => 'Symbol is required',
            'symbol.string' => 'Symbol must be a string',
            'symbol.uppercase' => 'Symbol must be uppercase',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    public function toDTO(): NewsBySymbolDto
    {
        return new NewsBySymbolDto($this->validated());
    }
}
