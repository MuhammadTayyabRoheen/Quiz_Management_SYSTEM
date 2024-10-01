<?php

namespace App\DTO;

class RejectStudentDTO
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function fromId(int $id): self
    {
        return new self($id);
    }
}
