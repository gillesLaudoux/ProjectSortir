<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StateRepository::class)]
class State
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 20)]
    private $libelle = "Créée";

    #[ORM\OneToMany(mappedBy: 'state', targetEntity: NightOut::class)]
    private $nightOuts;

    public function __construct()
    {
        $this->nightOuts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|NightOut[]
     */
    public function getNightOuts(): Collection
    {
        return $this->nightOuts;
    }

    public function addNightOut(NightOut $nightOut): self
    {
        if (!$this->nightOuts->contains($nightOut)) {
            $this->nightOuts[] = $nightOut;
            $nightOut->setState($this);
        }

        return $this;
    }

    public function removeNightOut(NightOut $nightOut): self
    {
        if ($this->nightOuts->removeElement($nightOut)) {
            // set the owning side to null (unless already changed)
            if ($nightOut->getState() === $this) {
                $nightOut->setState(null);
            }
        }

        return $this;
    }
}
