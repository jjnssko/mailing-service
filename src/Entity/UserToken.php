<?php

namespace App\Entity;

use App\Repository\UserTokenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserTokenRepository::class)]
class UserToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userTokens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $relatedUrl = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastUsage = null;

    /** @var Collection<int, EmailReceiver> */
    #[ORM\OneToMany(targetEntity: EmailReceiver::class, mappedBy: 'userToken')]
    private Collection $emailReceivers;

    /** @var Collection<int, EmailProcessLog> */
    #[ORM\OneToMany(targetEntity: EmailProcessLog::class, mappedBy: 'userToken', orphanRemoval: true)]
    private Collection $emailProcessLogs;

    public function __construct()
    {
        $this->emailReceivers = new ArrayCollection();
        $this->emailProcessLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRelatedUrl(): ?string
    {
        return $this->relatedUrl;
    }

    public function setRelatedUrl(string $relatedUrl): self
    {
        $this->relatedUrl = $relatedUrl;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getLastUsage(): ?\DateTimeInterface
    {
        return $this->lastUsage;
    }

    public function setLastUsage(?\DateTimeInterface $lastUsage): self
    {
        $this->lastUsage = $lastUsage;

        return $this;
    }

    /** @return Collection<int, EmailReceiver> */
    public function getEmailReceivers(): Collection
    {
        return $this->emailReceivers;
    }

    public function addEmailReceiverId(EmailReceiver $emailReceivers): self
    {
        if (!$this->emailReceivers->contains($emailReceivers)) {
            $this->emailReceivers->add($emailReceivers);
            $emailReceivers->setUserToken($this);
        }

        return $this;
    }

    public function removeEmailReceiverId(EmailReceiver $emailReceivers): self
    {
        if ($this->emailReceivers->removeElement($emailReceivers)) {
            if ($emailReceivers->getUserToken() === $this) {
                $emailReceivers->setUserToken(null);
            }
        }

        return $this;
    }

    /** @return Collection<int, EmailProcessLog> */
    public function getEmailProcessLogs(): Collection
    {
        return $this->emailProcessLogs;
    }

    public function addEmailProcessLog(EmailProcessLog $emailProcessLog): self
    {
        if (!$this->emailProcessLogs->contains($emailProcessLog)) {
            $this->emailProcessLogs->add($emailProcessLog);
            $emailProcessLog->setUserToken($this);
        }

        return $this;
    }

    public function removeEmailProcessLog(EmailProcessLog $emailProcessLog): self
    {
        if ($this->emailProcessLogs->removeElement($emailProcessLog)) {
            if ($emailProcessLog->getUserToken() === $this) {
                $emailProcessLog->setUserToken(null);
            }
        }

        return $this;
    }
}
