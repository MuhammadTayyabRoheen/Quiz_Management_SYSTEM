<?php

namespace App\DTO;

class ResetPasswordDTO
{
    public int $id;
    public string $password;
    public string $token;

    public function __construct(int $id, string $password, string $token)
    {
        $this->id = $id;
        $this->password = $password;
        $this->token = $token;
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            $validated['id'],
            $validated['password'],
            $validated['token']
        );
    }
}
