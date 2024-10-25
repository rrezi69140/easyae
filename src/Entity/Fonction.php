<?php

namespace App\Entity;

use App\Repository\FonctionRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\StatisticsPropertiesTrait;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FonctionRepository::class)]
#[ORM\HasLifecycleCallbacks]

class Fonction
{
    use StatisticsPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['fonction'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['fonction'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['fonction'])]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'fonctions')]
    private ?Contact $contacts = null;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getFonctions(): ?Contact
    {
        return $this->contacts;
    }

    public function setFonctions(?Contact $contacts): static
    {
        $this->contacts = $contacts;

        return $this;
    }

}
