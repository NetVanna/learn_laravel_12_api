<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneratePromptRequest extends FormRequest
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
            'image' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:10240',
                'min:1',
                'dimensions:min_width=100,min_height=100,max_width=10000,max_height=10000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'The image field is required.',
            'image.file' => 'The image must be a file.',
            'image.image' => 'The image must be an image.',
            'image.mimes' => 'The image must be a jpeg, png, jpg, gif, or svg.',
            'image.max' => 'The image may not be greater than 10240 kilobytes.',
            'image.dimensions' => 'The image dimensions are invalid.',
            'image.min' => 'The image must be at least 1 kilobyte.',
        ];
    }
}
