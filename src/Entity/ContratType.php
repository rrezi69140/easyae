<?php

namespace App\Entity;

use App\Repository\ContratTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\StatisticsPropertiesTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContratTypeRepository::class)]
#[ORM\HasLifecycleCallbacks]

class ContratType
{
    use StatisticsPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['contratType', 'contrat'])]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le ContratType doit avoir un Nom")]
    #[Assert\Length(min: 4, max: 255, minMessage: "Le ContratType doit avoir un Nom comportantau moins {{limit}} caractères")]
    #[Assert\NotNull(message: "Le ContratType doit avoir un Nom non null")]
    #[ORM\Column(length: 255)]
    #[Groups(['contratType', 'contrat'])]
    private ?string $name = null;

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
}
