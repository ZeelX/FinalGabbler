<?php

namespace App\Entity;

use App\Repository\GabsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GabsRepository::class)]
class Gabs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\ManyToOne(targetEntity: User::class,inversedBy: 'gabsList')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'gabsRef', targetEntity: UserLike::class)]
    private Collection $userLikes;

    #[ORM\Column(nullable: true)]
    private ?int $gabsInteraction = null;

    public function __construct()
    {
        $this->userLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, UserLike>
     */
    public function getUserLikes(): Collection
    {
        return $this->userLikes;
    }

    public function addUserLike(UserLike $userLike): self
    {
        if (!$this->userLikes->contains($userLike)) {
            $this->userLikes->add($userLike);
            $userLike->setGabsRef($this);
        }

        return $this;
    }

    public function removeUserLike(UserLike $userLike): self
    {
        if ($this->userLikes->removeElement($userLike)) {
            // set the owning side to null (unless already changed)
            if ($userLike->getGabsRef() === $this) {
                $userLike->setGabsRef(null);
            }
        }

        return $this;
    }

    public function getGabsInteraction(): ?int
    {
        return $this->gabsInteraction;
    }

    public function setGabsInteraction(?int $gabsInteraction): self
    {
        $this->gabsInteraction = $gabsInteraction;

        return $this;
    }
}
