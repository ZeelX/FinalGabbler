<?php

namespace App\Entity;

use App\Repository\UserInteractionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserInteractionRepository::class)]
class UserInteraction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userInteractions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $listOwner = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $relatedUser = null;

    #[ORM\Column]
    private ?int $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListOwner(): ?User
    {
        return $this->listOwner;
    }

    public function setListOwner(?User $listOwner): self
    {
        $this->listOwner = $listOwner;

        return $this;
    }

    public function getRelatedUser(): ?User
    {
        return $this->relatedUser;
    }

    public function setRelatedUser(?User $relatedUser): self
    {
        $this->relatedUser = $relatedUser;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
