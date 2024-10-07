<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /** @var Collection<int, UserToken> */
    #[ORM\OneToMany(targetEntity: UserToken::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userTokens;

    public function __construct()
    {
        $this->userTokens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, UserToken>
     */
    public function getUserTokens(): Collection
    {
        return $this->userTokens;
    }

    public function addType(UserToken $userToken): static
    {
        if (!$this->userTokens->contains($userToken)) {
            $this->userTokens->add($userToken);
            $userToken->setUser($this);
        }

        return $this;
    }

    public function removeType(UserToken $userToken): static
    {
        if ($this->userTokens->removeElement($userToken)) {
            // set the owning side to null (unless already changed)
            if ($userToken->getUser() === $this) {
                $userToken->setUser(null);
            }
        }

        return $this;
    }
}
