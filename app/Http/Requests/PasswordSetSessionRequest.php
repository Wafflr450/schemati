<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use App\Rules\MinecraftPlayerUUID;

class PasswordSetSessionRequest extends FormRequest
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
            'player_uuid' => ['required', 'string', new MinecraftPlayerUUID()],
        ];
    }

    public function messages(): array
    {
        return [
            'player_uuid.required' => 'A UUID is required',
            'player_uuid.string' => 'The UUID must be a string',
            'player_uuid.uuid' => 'The UUID must be a valid UUID',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException(
            $validator,
            response()->json(
                [
                    'errors' => $validator->errors(),
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
            ),
        );
    }
}
