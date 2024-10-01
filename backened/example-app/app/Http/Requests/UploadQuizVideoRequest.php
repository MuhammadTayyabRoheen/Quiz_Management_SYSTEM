<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadQuizVideoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'video' => 'required|mimes:mp4,mov,avi|max:10240',
        ];
    }
}
