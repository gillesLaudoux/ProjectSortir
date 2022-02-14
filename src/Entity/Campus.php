<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CampusRepository::class)]
class Campus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Regex("/^[a-z ,.'-]+$/i")]
    #[Assert\Length(min : 5, max : 100, minMessage: "Le nom du campus doit au moins être de 5 caractères",
        maxMessage: "Le nom du campus ne peut pas dépasser 100 caractères")]
    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'campus', targetEntity: User::class)]
    private $students;

    #[ORM\OneToMany(mappedBy: 'campus', targetEntity: NightOut::class)]
    private $nightsOut;

    public function __construct()
    {
        $this->students = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(User $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->setCampus($this);
        }

        return $this;
    }

    public function removeStudent(User $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getCampus() === $this) {
                $student->setCampus(null);
            }
        }

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
            $nightsOut->setCampus($this);
        }

        return $this;
    }

    public function removeNightsOut(NightOut $nightsOut): self
    {
        if ($this->nightsOut->removeElement($nightsOut)) {
            // set the owning side to null (unless already changed)
            if ($nightsOut->getCampus() === $this) {
                $nightsOut->setCampus(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->getName();
    }

}
