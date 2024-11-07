<?php

namespace App\Entity;

use App\Repository\FacturationModelRepository;
use App\Entity\Traits\StatisticsPropertiesTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FacturationModelRepository::class)]
#[ORM\HasLifecycleCallbacks]

class FacturationModel
{
    use StatisticsPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['facturationModel', 'client'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['facturationModel', 'client'])]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'facturationModel', cascade: ['persist', 'remove'])]
    private ?Client $client = null;

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        if ($client === null && $this->client !== null) {
            $this->client->setFacturationModel(null);
        }

        if ($client !== null && $client->getFacturationModel() !== $this) {
            $client->setFacturationModel($this);
        }

        $this->client = $client;

        return $this;
    }
}
