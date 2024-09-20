<?php

namespace App\Http\Requests;

use App\Rules\UniqueFields;
use App\Enums\PlayerSkill;
use App\Enums\PlayerPosition;
use Illuminate\Validation\Rules\Enum;

class PlayerUpdateRequest extends PlayerBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'position' => [new Enum(PlayerPosition::class)],
            'playerSkills' => 'array|min:1',
            'playerSkills.*' => ['array', new UniqueFields(['skill'])],
            'playerSkills.*.skill' => [new Enum(PlayerSkill::class)],
            'playerSkills.*.value' => 'int|min:0|max:255',
        ];
    }
}
