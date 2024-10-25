<?php

namespace App\Entity;

use App\Repository\ContactLinkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ContactLinkRepository::class)]
#[ORM\HasLifecycleCallbacks]

class ContactLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['contactLink'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['contactLink'])]
    private ?string $value = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['contactLink'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'contactLinks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContactLinkType $contactLinkType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getContactLinkType(): ?ContactLinkType
    {
        return $this->contactLinkType;
    }

    public function setContactLinkType(?ContactLinkType $contactLinkType): static
    {
        $this->contactLinkType = $contactLinkType;

        return $this;
    }
}
