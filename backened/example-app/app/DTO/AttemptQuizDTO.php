<?php

namespace App\DTO;

class AttemptQuizDTO
{
    public $answers;
    public $videoPath;

    // Constructor expects array data to initialize the DTO
    public function __construct(array $data)
    {
        $this->answers = $data['answers'];  // Correctly setting the answers
        $this->videoPath = $data['video'] ?? null;  // Handle video path, if available
    }

    // Create DTO from request data
    public static function fromRequest(array $data)
    {
        return new self($data); 
        
    $dto = new self($data);
    $dto->answers = $data['answers'];
    $dto->videoPath = $data['video'] ?? null;  // Use null if the video is not present
    return $dto; // Pass the request data to the constructor
    }
}
