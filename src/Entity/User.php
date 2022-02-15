<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    //#[Assert\NotNull]
    //#[Assert\Unique]
    //#[Assert\NotBlank]
    //#[Assert\Length(min : 3, max : 180, minMessage: "Votre pseudo doit au moins être de 3 caractères",
                    //maxMessage: "Votre pseudo ne peut pas dépasser 180 caractères")]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    //#[Assert\Regex("^(?=.{5,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$")]
    private $username;

    #[ORM\Column(type: 'json')]
    private $roles = ["ROLE_USER"];

    #[ORM\Column(type: 'string')]
    private $password;

//    #[Assert\Email]
//    #[Assert\NotNull]
//    #[Assert\Unique]
//    #[Assert\NotBlank]
    #[Assert\Length(
        min: 8, max: 50, minMessage: "Votre mail ne peut pas avoir moins de 8 caractères",
        maxMessage: "Votre mail ne peut pas faire plus de 50 caractères"
    )]
//        #[Assert\Regex("/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|
//        (?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|
//        (?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:
//        [\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|
//        (?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z]
//        [a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|
//        (?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|
//        (?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4})
//        {0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))
//        (?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD")]
    #[ORM\Column(type: 'string', length: 50)]
    private $email;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3, max: 50, minMessage: "Votre prénom ne peut pas avoir moins de 3 caractères",
        maxMessage: "Votre prénom ne peut pas faire plus de 50 caractères"
    )]
    //#[Assert\Regex("#/^[a-z ,.'-]+$/i")]
    #[ORM\Column(type: 'string', length: 50)]
    private $firstName;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2, max: 50, minMessage: "Votre nom ne peut pas avoir moins de 2 caractères",
        maxMessage: "Votre nom ne peut pas faire plus de 50 caractères"
    )]
    //#[Assert\Regex("/^[a-z ,.'-]+$/i")]
    #[ORM\Column(type: 'string', length: 50)]
    private $lastName;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 8, max: 50, minMessage: "Votre mail ne peut pas avoir moins de 8 caractères",
        maxMessage: "Votre mail ne peut pas faire plus de 50 caractères"
    )]
    #[Assert\Length(
        min : 10, max: 10, minMessage: "Un numéro de téléphone valide est composé de 10 chiffres", maxMessage: "
        Un numéro de téléphone valide est composé de 10 chiffres"
    )]
    //#[Assert\Regex("^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$")]
    #[ORM\Column(type: 'string', length: 10)]
    private $phoneNumber;

    #[ORM\Column(type: 'boolean')]
    private $administrator = false;

    #[ORM\Column(type: 'boolean')]
    private $isActivated = false;

    #[ORM\ManyToMany(targetEntity: NightOut::class, mappedBy: 'participants')]
    private $nightsOut;

    #[ORM\OneToMany(mappedBy: 'organizer', targetEntity: NightOut::class, orphanRemoval: true)]
    private $nightsOutOrganizer;

    #[ORM\ManyToOne(targetEntity: Campus::class, inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private $campus;

    #[ORM\OneToOne(targetEntity: Avatar::class, cascade: ['persist', 'remove'])]
    private $avatar;


    public function __construct()
    {
        $this->nightsOut = new ArrayCollection();
        $this->nightsOutOrganizer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAdministrator(): ?bool
    {
        return $this->administrator;
    }

    public function setAdministrator(bool $administrator): self
    {
        $this->administrator = $administrator;

        return $this;
    }

    public function getIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    /**
     * @return Collection|NightOut[]
     */
    public function getNightsOut(): Collection
    {
        return $this->nightsOut;
    }

    public function addNightOut(NightOut $nightOut): self
    {
        if (!$this->nightsOut->contains($nightOut)) {
            $this->nightsOut[] = $nightOut;
            $nightOut->addParticipant($this);
        }

        return $this;
    }

    public function removeNightOut(NightOut $nightOut): self
    {
        if ($this->nightsOut->removeElement($nightOut)) {
            $nightOut->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection|NightOut[]
     */
    public function getNightsOutOrganizer(): Collection
    {
        return $this->nightsOutOrganizer;
    }

    public function addNightsOutOrganizer(NightOut $nightsOutOrganizer): self
    {
        if (!$this->nightsOutOrganizer->contains($nightsOutOrganizer)) {
            $this->nightsOutOrganizer[] = $nightsOutOrganizer;
            $nightsOutOrganizer->setOrganizer($this);
        }

        return $this;
    }

    public function removeNightsOutOrganizer(NightOut $nightsOutOrganizer): self
    {
        if ($this->nightsOutOrganizer->removeElement($nightsOutOrganizer)) {
            // set the owning side to null (unless already changed)
            if ($nightsOutOrganizer->getOrganizer() === $this) {
                $nightsOutOrganizer->setOrganizer(null);
            }
        }

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

//    public function __toString()
//    {
//        return $this->campus  ;
//    }

public function getAvatar(): ?Avatar
{
    return $this->avatar;
}

public function setAvatar(?Avatar $avatar): self
{
    $this->avatar = $avatar;

    return $this;
}


}
