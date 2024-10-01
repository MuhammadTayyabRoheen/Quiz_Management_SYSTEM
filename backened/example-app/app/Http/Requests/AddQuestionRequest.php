<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddQuestionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'question_text' => 'required|string|max:255',
            'options' => 'required|array|min:2',  // Ensures that there are multiple-choice options
            'correct_answer' => 'required|string|max:255',
        ];
    }
}
