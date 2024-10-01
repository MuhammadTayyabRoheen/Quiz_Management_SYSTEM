<?php

namespace App\DTO;

class AddQuestionDTO
{
    public string $questionText;
    public string $correctAnswer;
    public array $options;

    public function __construct(string $questionText, string $correctAnswer, array $options)
    {
        $this->questionText = $questionText;
        $this->correctAnswer = $correctAnswer;
        $this->options = $options;
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            $validated['question_text'],
            $validated['correct_answer'],
            $validated['options']
        );
    }
}
