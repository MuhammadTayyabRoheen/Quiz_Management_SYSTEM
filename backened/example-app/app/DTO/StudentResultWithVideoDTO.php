<?php

namespace App\DTO;

class StudentResultWithVideoDTO
{
    public $quizId;
    public $studentId;
    public $score;
    public $videoUrl;

    public function __construct($quizId, $studentId, $score, $videoUrl)
    {
        $this->quizId = $quizId;
        $this->studentId = $studentId;
        $this->score = $score;
        $this->videoUrl = $videoUrl;
    }

    public static function fromArray(array $data)
    {
        return new self(
            $data['quizId'],
            $data['studentId'],
            $data['score'],
            $data['videoUrl']
        );
    }
}
