<?php

namespace App\DTO;

class SubmitFormDTO
{
    public string $name;
    public string $email;
    public string $cvPath;

    public function __construct(string $name, string $email, string $cvPath)
    {
        $this->name = $name;
        $this->email = $email;
        $this->cvPath = $cvPath;
    }

    public static function fromRequest(array $validated, string $cvPath): self
    {
        return new self(
            $validated['name'],
            $validated['email'],
            $cvPath
        );
    }
}
