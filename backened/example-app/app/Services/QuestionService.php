<?php

namespace App\Services;

use App\Models\Question;

class QuestionService
{
    public function addQuestion($quizId, $validated)
    {
        return Question::create([
            'quiz_id' => $quizId,
            'question_text' => $validated['question_text'],
            'correct_answer' => $validated['correct_answer'],
        ]);
    }
}
