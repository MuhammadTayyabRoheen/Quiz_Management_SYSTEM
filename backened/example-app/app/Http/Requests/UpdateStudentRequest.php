<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize()
    {
        // Ensure that only admins can update student details
        return $this->user()->hasRole('admin') || $this->user()->hasRole('supervisor');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->route('studentId'),
            'status' => 'nullable|string|in:active,approved,rejected',
        ];
    }
}
