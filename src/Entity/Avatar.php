<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AvatarRepository::class)]
class Avatar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min : 5, max : 255, minMessage: "Le nom du fichier est trop court !", maxMessage: "Le nom du 
    fichier est trop long !")]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $AvatarFileName;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255, maxMessage: "Le format de fichier est trop long")]
    //#[Assert\Regex("/^[a-z ,.'-]+$/i")]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $AvatarFileType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvatarFileName(): ?string
    {
        return $this->AvatarFileName;
    }

    public function setAvatarFileName(?string $AvatarFileName): self
    {
        $this->AvatarFileName = $AvatarFileName;

        return $this;
    }

    public function getAvatarFileType(): ?string
    {
        return $this->AvatarFileType;
    }

    public function setAvatarFileType(?string $AvatarFileType): self
    {
        $this->AvatarFileType = $AvatarFileType;

        return $this;
    }

    public function __toString(): string
    {
        return $this->AvatarFileName;
    }
}
