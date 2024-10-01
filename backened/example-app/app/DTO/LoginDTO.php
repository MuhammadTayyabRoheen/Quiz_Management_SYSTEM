<?php

namespace App\DTO;

class LoginDTO
{
    public string $email;
    public string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            $validated['email'],
            $validated['password']
        );
    }
}
