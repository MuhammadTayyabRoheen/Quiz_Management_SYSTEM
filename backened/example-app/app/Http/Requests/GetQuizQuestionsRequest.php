<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetQuizQuestionsRequest extends FormRequest
{
    public function authorize()
    {
        // You can implement additional logic to restrict access if necessary
        return true;  // Allow all students for now
    }

    public function rules()
    {
        return [];
    }
}
