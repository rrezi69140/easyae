<?php

namespace App\Entity;

use App\Repository\InfoTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\StatisticsPropertiesTrait;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: InfoTypeRepository::class)]
class InfoType
{
    use StatisticsPropertiesTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['infoType'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['infoType'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['infoType'])]
    private ?string $info = null;

    #[ORM\Column(length: 24)]
    #[Groups(['infoType'])]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(string $info): static
    {
        $this->info = $info;

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
