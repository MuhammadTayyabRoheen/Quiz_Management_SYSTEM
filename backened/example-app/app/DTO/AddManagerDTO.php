<?php

namespace App\DTO;

class AddManagerDTO
{
    public string $name;
    public string $email;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            $validated['name'],
            $validated['email']
        );
    }
}
