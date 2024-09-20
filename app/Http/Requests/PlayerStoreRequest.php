<?php

namespace App\Http\Requests;

use App\Rules\UniqueFields;
use App\Enums\PlayerSkill;
use App\Enums\PlayerPosition;
use Illuminate\Validation\Rules\Enum;

class PlayerStoreRequest extends PlayerBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'position' => ['required', new Enum(PlayerPosition::class)],
            'playerSkills' => 'required|array|min:1',
            'playerSkills.*' => ['array', new UniqueFields(['skill'])],
            'playerSkills.*.skill' => ['required', new Enum(PlayerSkill::class)],
            'playerSkills.*.value' => 'required|int|min:0|max:255',
        ];
    }
}
