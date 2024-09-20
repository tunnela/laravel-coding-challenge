<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class PlayerBaseRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'name.*' => 'Invalid value for name: :input',
            'position.*' => 'Invalid value for position: :input',
            'playerSkills.array' => 'Invalid value for playerSkills: :input',
            'playerSkills.*' => 'Invalid value for playerSkills: empty',
            'playerSkills.*.skill.*' => 'Invalid value for skill: :input',
            'playerSkills.*.value.*' => 'Invalid value for value: :input',
        ];
    }
}
