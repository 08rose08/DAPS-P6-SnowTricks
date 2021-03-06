<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ImageTrickRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ImageTrickRepository::class)
 */
class ImageTrick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $src;

    /**
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="imageTricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Trick;

    /**
     * @Assert\Image(mimeTypes = {"image/jpeg", "image/jpg", "image/png"}, mimeTypesMessage = "Format invalide")
     */
    private $file;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function setSrc(string $src): self
    {
        $this->src = $src;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->Trick;
    }

    public function setTrick(?Trick $Trick): self
    {
        $this->Trick = $Trick;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $file;
    }
}
