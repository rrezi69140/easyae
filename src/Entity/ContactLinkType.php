<?php

namespace App\Entity;

use App\Entity\Traits\StatisticsPropertiesTrait;
use App\Repository\ContactLinkTypeRepository;
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
