<?php

namespace App\Entity;

use App\Repository\UserLikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserLikeRepository::class)]
class UserLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userLikes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gabs $gabsRef = null;

    #[ORM\ManyToOne(inversedBy: 'userLikes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGabsRef(): ?Gabs
    {
        return $this->gabsRef;
    }

    public function setGabsRef(?Gabs $gabsRef): self
    {
        $this->gabsRef = $gabsRef;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
