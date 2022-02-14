<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvatarRepository::class)]
class Avatar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $AvatarFileName;

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
}
