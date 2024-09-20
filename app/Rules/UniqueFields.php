<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/*
 * Validator to make sure objects in 
 * the array are unique based on selected fields.
 */
class UniqueFields implements Rule
{
    protected $combos = [];

    protected $fields = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $fields)
    {
        sort($fields);

        $this->fields = $fields;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $combo = collect($this->fields)->map(function($field) use ($value) {
            return $value[$field] ?? '';
        })->join(',');

        if (in_array($combo, $this->combos)) {
            return false;
        }
        $this->combos[] = $combo;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid value for :attribute: non_unique';
    }
}
