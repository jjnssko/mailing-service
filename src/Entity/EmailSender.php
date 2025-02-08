<?php

namespace App\Entity;

use App\Repository\EmailReceiverRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailReceiverRepository::class)]
class EmailSender
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $mailProtocol = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $mailServer = null;

    #[ORM\Column(length: 255)]
    private ?string $mailServerPort = null;

    #[ORM\OneToOne(inversedBy: 'emailSender', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserToken $userToken = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): EmailSender
    {
        $this->fullName = $fullName;
        return $this;
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

    public function getMailProtocol(): ?string
    {
        return $this->mailProtocol;
    }

    public function setMailProtocol(?string $mailProtocol): EmailSender
    {
        $this->mailProtocol = $mailProtocol;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): EmailSender
    {
        $this->password = $password;
        return $this;
    }

    public function getMailServer(): ?string
    {
        return $this->mailServer;
    }

    public function setMailServer(?string $mailServer): EmailSender
    {
        $this->mailServer = $mailServer;
        return $this;
    }

    public function getMailServerPort(): ?string
    {
        return $this->mailServerPort;
    }

    public function setMailServerPort(?string $mailServerPort): EmailSender
    {
        $this->mailServerPort = $mailServerPort;
        return $this;
    }

    public function getUserToken(): ?UserToken
    {
        return $this->userToken;
    }

    public function setUserToken(UserToken $userToken): static
    {
        $this->userToken = $userToken;

        return $this;
    }
}
