<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use App\Entity\Traits\StatisticsPropertiesTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\HasLifecycleCallbacks]

class Contact
{

    use StatisticsPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $name = null;

    /**
     * @var Collection<int, ContactLink>
     */
    #[ORM\OneToMany(targetEntity: ContactLink::class, mappedBy: 'contact')]
    private Collection $link;
     
     * @var Collection<int, Fonction>
     */
    #[ORM\OneToMany(targetEntity: Fonction::class, mappedBy: 'Fonctions')]
    private Collection $fonctions;

    public function __construct()
    {
         $this->link = new ArrayCollection();
        $this->fonctions = new ArrayCollection();
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
    public function getLink(): Collection
    {
        return $this->link;
    }

    public function addLink(ContactLink $link): static
    {
        if (!$this->link->contains($link)) {
            $this->link->add($link);
            $link->setContact($this);
     * @return Collection<int, Fonction>
     */
    public function getFonctions(): Collection
    {
        return $this->fonctions;
    }

    public function addFonction(Fonction $fonction): static
    {
        if (!$this->fonctions->contains($fonction)) {
            $this->fonctions->add($fonction);
            $fonction->setFonctions($this);
        }

        return $this;
    }

    public function removeLink(ContactLink $link): static
    {
        if ($this->link->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getContact() === $this) {
                $link->setContact(null);
    public function removeFonction(Fonction $fonction): static
    {
        if ($this->fonctions->removeElement($fonction)) {
            // set the owning side to null (unless already changed)
            if ($fonction->getFonctions() === $this) {
                $fonction->setFonctions(null);
            }
        }

        return $this;
    }


}
