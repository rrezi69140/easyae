<?php

namespace App\Entity;

use App\Entity\Traits\StatisticsPropertiesTrait;
use App\Repository\FacturationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FacturationRepository::class)]
#[ORM\HasLifecycleCallbacks]

class Facturation
{

    use StatisticsPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['facturation'])]
    private ?int $id = null;

    #[ORM\Column(length: 24)]
    #[Groups(['facturation'])]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    #[Groups(['facturation'])]
    private ?string $name = null;

    #[ORM\OneToOne(inversedBy: 'facturation')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['facturation'])]
    private ?Contrat $contrat = null;

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

    public function getcontrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setcontrat(Contrat $contrat): static
    {
        $this->contrat = $contrat;

        return $this;
    }
}
