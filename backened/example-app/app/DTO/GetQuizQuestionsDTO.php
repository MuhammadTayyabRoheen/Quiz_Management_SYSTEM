<?php

namespace App\DTO;

class GetQuizQuestionsDTO
{
    public int $quizId;

    public function __construct(int $quizId)
    {
        $this->quizId = $quizId;
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            $validated['quizId']
        );
    }
}
