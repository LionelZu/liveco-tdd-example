<?php

namespace App\Entity;

use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdherentRepository::class)]
class Adherent
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'adherent', cascade: ['persist'], targetEntity: Achat::class, orphanRemoval: true)]
    private Collection $achats;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $cotisation = null;

    public function __construct()
    {
        $this->achats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Achat>
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats->add($achat);
            $achat->setAdherent($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getAdherent() === $this) {
                $achat->setAdherent(null);
            }
        }

        return $this;
    }

    public function getCotisation(): ?string
    {
        return $this->cotisation;
    }

    public function setCotisation(string $cotisation): self
    {
        $this->cotisation = $cotisation;

        return $this;
    }

    public function computeTotal(): float
    {
        $total = 0;
        foreach ($this->achats as &$achat) {
            $total = $total + $achat->getCurrentPrice() * $achat->getQuantity();
        }
        return $total;
    }


    public function computeNegociateTotal(): float
    {
        $total = 0;
        foreach ($this->achats as &$achat) {
            $total = $total + $achat->getNegociatePrice() * $achat->getQuantity();
        }
        return $total;
    }
}
