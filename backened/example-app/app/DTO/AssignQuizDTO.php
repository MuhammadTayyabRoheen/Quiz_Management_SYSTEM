<?php

namespace App\DTO;

class AssignQuizDTO
{
    public string $title;
    public string $description;

    public function __construct(string $title, string $description)
    {
        $this->title = $title;
        $this->description = $description;
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            $validated['title'],
            $validated['description']
        );
    }
}
