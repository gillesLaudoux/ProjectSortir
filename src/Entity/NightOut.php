<?php

namespace App\Entity;

use App\Repository\NightOutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NightOutRepository::class)]
class NightOut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min : 5, max : 250, minMessage: "Le nom de la sortie doit au moins être de 5 caractères",
        maxMessage: "Le nom de la sortie ne peut pas dépasser 250 caractères")]
    //TODO REFAIRE les regex ou les vérifier
    //#[Assert\Regex("#^(?=.{5,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$#")]
    #[ORM\Column(type: 'string', length: 250)]
    private $name;


    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(new \DateTime())]
    #[ORM\Column(type: 'datetime')]
    private $startingTime;



    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(new \DateTime())]
    #[ORM\Column(type: 'datetime')]
    private $dueDateInscription;

    #[Assert\Type(type: 'integer', message: "Accepte que les entiers")]
    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    private $nbInscriptionMax;

    #[Assert\Length(min:10, max: 500, minMessage: "Votre description n'est pas assez longue", maxMessage: "
    Votre description est trop longue !")]
    //#[Assert\Regex("#^(?=.{5,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$#")]
    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    private $description;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'nightsOut')]
    private $participants;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'nightsOutOrganizer')]
    #[ORM\JoinColumn(nullable: false)]
    private $organizer;

    #[ORM\ManyToOne(targetEntity: Campus::class, inversedBy: 'nightsOut')]
    #[ORM\JoinColumn(nullable: false)]
    private $campus;

    #[ORM\ManyToOne(targetEntity: Place::class, inversedBy: 'nightsOut')]
    #[ORM\JoinColumn(nullable: false)]
    private $places;

    #[ORM\ManyToOne(targetEntity: State::class, inversedBy: 'nightOuts')]
    #[ORM\JoinColumn(nullable: false)]
    private $state;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'nightOuts')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\Column(type: 'datetime')]
    private $endingTime;



    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->setStartingTime(new \DateTime());
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

    public function getStartingTime(): ?\DateTimeInterface
    {
        return $this->startingTime;
    }

    public function setStartingTime(\DateTimeInterface $startingTime): self
    {
        $this->startingTime = $startingTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDueDateInscription(): ?\DateTimeInterface
    {
        return $this->dueDateInscription;
    }

    public function setDueDateInscription(\DateTimeInterface $dueDateInscription): self
    {
        $this->dueDateInscription = $dueDateInscription;

        return $this;
    }

    public function getNbInscriptionMax(): ?int
    {
        return $this->nbInscriptionMax;
    }

    public function setNbInscriptionMax(?int $nbInscriptionMax): self
    {
        $this->nbInscriptionMax = $nbInscriptionMax;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getPlaces(): ?Place
    {
        return $this->places;
    }

    public function setPlaces(?Place $places): self
    {
        $this->places = $places;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __toString()
    {
        return $this->category . $this->campus;// . $this->places ;
    }

    public function getEndingTime(): ?\DateTimeInterface
    {
        return $this->endingTime;
    }

    public function setEndingTime(\DateTimeInterface $endingTime): self
    {
        $this->endingTime = $endingTime;

        return $this;
    }

}
