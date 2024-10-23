<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
// use Doctrine\Common\Collections\ArrayCollection;
// use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $name = null;

    // #[ORM\OneToMany(targetEntity: ContactLink::class, mappedBy: "contact", cascade: ["persist", "remove"])]
    // private Collection $links;

    // #[ORM\ManyToOne(targetEntity: Fonction::class, inversedBy: "contacts")]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?Fonction $fonction = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 24)]
    private ?string $status = null;

    // public function __construct()
    // {
    //     $this->links = new ArrayCollection();
    // }

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

    // /**
    //  * @return Collection<int, ContactLink>
    //  */
    // public function getLinks(): Collection
    // {
    //     return $this->links;
    // }

    // public function addLink(ContactLink $link): self
    // {
    //     if (!$this->links->contains($link)) {
    //         $this->links[] = $link;
    //         $link->setContact($this);
    //     }

    //     return $this;
    // }


    // public function getFonction(): ?Fonction
    // {
    //     return $this->fonction;
    // }

    // public function setFonction(?Fonction $fonction): self
    // {
    //     $this->fonction = $fonction;

    //     return $this;
    // }

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
}
