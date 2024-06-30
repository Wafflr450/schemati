<?php

namespace App\Http\Requests\Schematic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Rules\SchematicIdRule;

class SchematicDownloadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assuming anyone can download schematics
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // If 'id' is not in the request (POST data), but it's in the route parameters (GET),
        // add it to the request data
        if (!$this->has('id') && $this->route('id')) {
            $this->merge(['id' => $this->route('id')]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'string', new SchematicIdRule()],
            'format' => 'sometimes|in:schem,schematic',
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
