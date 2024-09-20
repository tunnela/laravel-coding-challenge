<?php

namespace App\Http\Requests;

use App\Rules\UniqueFields;
use App\Enums\PlayerSkill;
use App\Enums\PlayerPosition;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class TeamProcessRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'requirements' => 'required|array|min:1',
            'requirements.*.mainSkill' => ['required', new Enum(PlayerSkill::class)],
            'requirements.*.position' => ['required', new Enum(PlayerPosition::class)],
            'requirements.*.numberOfPlayers' => 'required|integer|min:1',
            'requirements.*' => ['array', new UniqueFields(['mainSkill', 'position'])]
        ];
    }

    public function messages()
    {
        return [
            'requirements.*' => 'Invalid value for requirements: empty',
            'requirements.*.mainSkill.*' => 'Invalid value for mainSkill: :input',
            'requirements.*.position.*' => 'Invalid value for position: :input',
            'requirements.*.numberOfPlayers.*' => 'Invalid value for numberOfPlayers: :input',
        ];
    }

    public function validationData()
    {
        return ['requirements' => $this->all()];
    }
}
