<?php

namespace App\Entity;

use App\Entity\Traits\StatisticsPropertiesTrait;
use App\Repository\ContactLinkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: ContactLinkRepository::class)]
#[ORM\HasLifecycleCallbacks]

class ContactLink
{
    use StatisticsPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['contactLink'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['contactLink'])]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'link')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contact $contact = null;

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

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

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
