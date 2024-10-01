<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetStudentResultWithVideoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'student_id' => 'required|integer|exists:users,id',
            'quiz_id' => 'required|integer|exists:quizzes,id',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
