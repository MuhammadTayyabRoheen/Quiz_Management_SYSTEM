<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignQuizRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }
}
