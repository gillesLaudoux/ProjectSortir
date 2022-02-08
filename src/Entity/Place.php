<?php

namespace App\Entity;

use App\Repository\PlacesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlacesRepository::class)]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 150)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $street;

    #[ORM\OneToMany(mappedBy: 'places', targetEntity: NightOut::class, orphanRemoval: true)]
    private $nightsOut;

    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'places')]
    #[ORM\JoinColumn(nullable: false)]
    private $city;

    public function __construct()
    {
        $this->nightsOut = new ArrayCollection();
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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return Collection|NightOut[]
     */
    public function getNightsOut(): Collection
    {
        return $this->nightsOut;
    }

    public function addNightsOut(NightOut $nightsOut): self
    {
        if (!$this->nightsOut->contains($nightsOut)) {
            $this->nightsOut[] = $nightsOut;
            $nightsOut->setPlaces($this);
        }

        return $this;
    }

    public function removeNightsOut(NightOut $nightsOut): self
    {
        if ($this->nightsOut->removeElement($nightsOut)) {
            // set the owning side to null (unless already changed)
            if ($nightsOut->getPlaces() === $this) {
                $nightsOut->setPlaces(null);
            }
        }

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
