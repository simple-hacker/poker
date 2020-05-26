<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGameTransactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'game_id' => 'required|integer',
            'game_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'comments' => 'sometimes|nullable|string'
        ];
    }
}