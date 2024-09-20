<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayerDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // This is lazy man's version of the required Bearer token auth.
        // It wasn't quite clear if you wanted to validate the token 
        // against real OAuth tokens in the database...
        return request()->bearerToken() == config('auth.bearer_token');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
