<?php

namespace App\Entity;

use App\Repository\FacturationModelRepository;
use App\Entity\Traits\StatisticsPropertiesTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FacturationModelRepository::class)]
#[ORM\HasLifecycleCallbacks]

class FacturationModel
{
    use StatisticsPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['facturationModel'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['facturationModel'])]
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
