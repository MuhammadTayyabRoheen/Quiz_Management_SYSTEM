<?php

namespace App\DTO;

class UploadQuizVideoDTO
{
    public string $videoPath;

    public function __construct(string $videoPath)
    {
        $this->videoPath = $videoPath;
    }

    public static function fromFilePath(string $videoPath): self
    {
        return new self($videoPath);
    }
}

