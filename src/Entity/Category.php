<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Regex("/^[a-z ,.'-]+$/i")]
    #[Assert\Length(min : 5, max : 255, minMessage: "Le nom de la catégorie doit au moins être de 5 caractères",
        maxMessage: "Le nom de la catégorie ne peut pas dépasser 255 caractères")]
    #[ORM\Column(type: 'string', length: 255)]
    private $libelle;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: NightOut::class)]
    private $nightOuts;

    public function __construct()
    {
        $this->nightOuts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $nightOut->setCategory($this);
        }

        return $this;
    }

    public function removeNightOut(NightOut $nightOut): self
    {
        if ($this->nightOuts->removeElement($nightOut)) {
            // set the owning side to null (unless already changed)
            if ($nightOut->getCategory() === $this) {
                $nightOut->setCategory(null);
            }
        }

        return $this;
    }

//        public function __toString()
//    {
//        return $this->campus  ;
//    }
}
