<?php

namespace App\Entity;

use App\Repository\VideoTrickRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VideoTrickRepository::class)
 */
class VideoTrick
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
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="videoTricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Trick;

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
}
