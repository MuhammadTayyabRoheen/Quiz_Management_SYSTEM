<?php

namespace App\DTO;

class AssignPermissionDTO
{
    public int $userId;
    public string $permission;

    public function __construct(int $userId, string $permission)
    {
        $this->userId = $userId;
        $this->permission = $permission;
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            $validated['user_id'],
            $validated['permission']
        );
    }
}
