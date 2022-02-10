<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $username;

    #[ORM\Column(type: 'json')]
    private $roles = ["ROLE_USER"];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 50)]
    private $email;

    #[ORM\Column(type: 'string', length: 50)]
    private $firstName;

    #[ORM\Column(type: 'string', length: 50)]
    private $lastName;

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

    public function __toString()
    {
        return $this->campus  ;
    }


}
