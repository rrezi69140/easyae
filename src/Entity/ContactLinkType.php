<?php

namespace App\Entity;

use App\Entity\Traits\StatisticsPropertiesTrait;
use App\Repository\ContactLinkTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ContactLinkTypeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ContactLinkType
{
    use StatisticsPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['contactLinkType'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['contactLinkType'])]
    private ?string $name = null;

    /**
     * @var Collection<int, ContactLink>
     */
    #[ORM\OneToMany(targetEntity: ContactLink::class, mappedBy: 'contactLinkType')]
    #[Groups(['contactLink'])]
    private Collection $contactLinks;

    public function __construct()
    {
        $this->contactLinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, ContactLink>
     */
    public function getContactLinks(): Collection
    {
        return $this->contactLinks;
    }

    public function addContactLink(ContactLink $contactLink): static
    {
        if (!$this->contactLinks->contains($contactLink)) {
            $this->contactLinks->add($contactLink);
            $contactLink->setContactLinkType($this);
        }

        return $this;
    }

    public function removeContactLink(ContactLink $contactLink): static
    {
        if ($this->contactLinks->removeElement($contactLink)) {
            // set the owning side to null (unless already changed)
            if ($contactLink->getContactLinkType() === $this) {
                $contactLink->setContactLinkType(null);
            }
        }

        return $this;
    }
}
