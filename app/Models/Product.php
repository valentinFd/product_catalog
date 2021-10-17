<?php

namespace App\Models;

class Product
{
    private ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    private string $category;

    public function getCategory(): string
    {
        return $this->category;
    }

    private int $quantity;

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    private int $userId;

    public function getUserId(): int
    {
        return $this->userId;
    }

    private ?string $createdAt;

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    private ?string $updatedAt;

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function __construct(string $name, string $category, int $quantity, ?int $userId = null,
                                ?int   $id = null, ?string $createdAt = null, ?string $updatedAt = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->quantity = $quantity;
        $this->userId = $userId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}
