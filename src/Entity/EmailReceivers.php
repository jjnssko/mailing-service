<?php

namespace App\Entity;

use App\Repository\EmailReceiversRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailReceiversRepository::class)]
class EmailReceivers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'emailReceivers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserToken $userToken = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /** @var Collection<int, EmailProcessLog> */
    #[ORM\OneToMany(targetEntity: EmailProcessLog::class, mappedBy: 'emailReceiver')]
    private Collection $emailProcessLogs;

    public function __construct()
    {
        $this->emailProcessLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserToken(): ?UserToken
    {
        return $this->userToken;
    }

    public function setUserToken(?UserToken $userToken): self
    {
        $this->userToken = $userToken;

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

    /**
     * @return Collection<int, EmailProcessLog>
     */
    public function getEmailProcessLogs(): Collection
    {
        return $this->emailProcessLogs;
    }

    public function addEmailProcessLog(EmailProcessLog $emailProcessLog): self
    {
        if (!$this->emailProcessLogs->contains($emailProcessLog)) {
            $this->emailProcessLogs->add($emailProcessLog);
            $emailProcessLog->setEmailReceiver($this);
        }

        return $this;
    }

    public function removeEmailProcessLog(EmailProcessLog $emailProcessLog): self
    {
        if ($this->emailProcessLogs->removeElement($emailProcessLog)) {
            // set the owning side to null (unless already changed)
            if ($emailProcessLog->getEmailReceiver() === $this) {
                $emailProcessLog->setEmailReceiver(null);
            }
        }

        return $this;
    }
}
