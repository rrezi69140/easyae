<?php

namespace App\Entity;

use App\Repository\HistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoryRepository::class)]
#[ORM\HasLifecycleCallbacks]

class History
{  
    use StatisticsPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    

    #[ORM\Column(length: 255)]
    private ?string $serializedPayload = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    


    public function getSerializedPayload(): ?string
    {
        return $this->serializedPayload;
    }

    public function setSerializedPayload(string $serializedPayload): static
    {
        $this->serializedPayload = $serializedPayload;

        return $this;
    }
}
