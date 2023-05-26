<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     */
    #[ORM\Column (nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Gabs::class)]
    private Collection $gabsList;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserLike::class)]
    private Collection $userLikes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'listOwner', targetEntity: UserInteraction::class)]
    private Collection $userInteractions;

    #[ORM\Column]
    private ?bool $isPremium = false;

    #[ORM\Column]
    private ?bool $isPrivate = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateStartSubscription = null;

    public function __construct()
    {
        $this->gabsList = new ArrayCollection();
        $this->userLikes = new ArrayCollection();
        $this->userInteractions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Gabs>
     */
    public function getGabsList(): Collection
    {
        return $this->gabsList;
    }

    public function addGabsList(Gabs $gabsList): self
    {
        if (!$this->gabsList->contains($gabsList)) {
            $this->gabsList->add($gabsList);
            $gabsList->setAuthor($this);
        }

        return $this;
    }

    public function removeGabsList(Gabs $gabsList): self
    {
        if ($this->gabsList->removeElement($gabsList)) {
            // set the owning side to null (unless already changed)
            if ($gabsList->getAuthor() === $this) {
                $gabsList->setAuthor(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

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
            $userLike->setUser($this);
        }

        return $this;
    }

    public function removeUserLike(UserLike $userLike): self
    {
        if ($this->userLikes->removeElement($userLike)) {
            // set the owning side to null (unless already changed)
            if ($userLike->getUser() === $this) {
                $userLike->setUser(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(){
        return $this->getName();
    }

    /**
     * @return Collection<int, UserInteraction>
     */
    public function getUserInteractions(): Collection
    {
        return $this->userInteractions;
    }

    public function addUserInteraction(UserInteraction $userInteraction): self
    {
        if (!$this->userInteractions->contains($userInteraction)) {
            $this->userInteractions->add($userInteraction);
            $userInteraction->setListOwner($this);
        }

        return $this;
    }

    public function removeUserInteraction(UserInteraction $userInteraction): self
    {
        if ($this->userInteractions->removeElement($userInteraction)) {
            // set the owning side to null (unless already changed)
            if ($userInteraction->getListOwner() === $this) {
                $userInteraction->setListOwner(null);
            }
        }

        return $this;
    }

    public function isIsPremium(): ?bool
    {
        return $this->isPremium;
    }

    public function setIsPremium(bool $isPremium): self
    {
        $this->isPremium = $isPremium;

        return $this;
    }

    public function isIsPrivate(): ?bool
    {
        return $this->isPrivate;
    }

    public function setIsPrivate(bool $isPrivate): self
    {
        $this->isPrivate = $isPrivate;

        return $this;
    }

    public function getDateStartSubscription(): ?\DateTimeInterface
    {
        return $this->dateStartSubscription;
    }

    public function setDateStartSubscription(?\DateTimeInterface $dateStartSubscription): self
    {
        $this->dateStartSubscription = $dateStartSubscription;

        return $this;
    }
}
