<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttemptQuizRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer',
            'answers.*.answer' => 'required|string',
            'video' => 'nullable|file|mimes:webm', // Allow the video field to be optional

        ];
    }
}
