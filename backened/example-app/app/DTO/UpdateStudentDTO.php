<?php

namespace App\DTO;

class UpdateStudentDTO
{
    public string $name;
    public string $email;
    public ?string $status;

    public function __construct(string $name, string $email, ?string $status)
    {
        $this->name = $name;
        $this->email = $email;
        $this->status = $status;
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            $validated['name'],
            $validated['email'],
            $validated['status'] ?? null
        );
    }
}
