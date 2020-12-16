<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 * 
 */
class Trick
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
    private $name;

    /**
     * @ORM\Column(type="text")

     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=FigType::class, inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $FigType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $editAt;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="trick", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=ImageTrick::class, mappedBy="Trick", orphanRemoval=true, cascade={"persist"})
     */
    private $imageTricks;

    /**
     * @ORM\OneToMany(targetEntity=VideoTrick::class, mappedBy="Trick", orphanRemoval=true)
     */
    private $videoTricks;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->imageTricks = new ArrayCollection();
        $this->videoTricks = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getFigType(): ?FigType
    {
        return $this->FigType;
    }

    public function setFigType(?FigType $FigType): self
    {
        $this->FigType = $FigType;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEditAt(): ?\DateTimeInterface
    {
        return $this->editAt;
    }

    public function setEditAt(\DateTimeInterface $editAt): self
    {
        $this->editAt = $editAt;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ImageTrick[]
     */
    public function getImageTricks(): Collection
    {
        return $this->imageTricks;
    }

    public function addImageTrick(ImageTrick $imageTrick): self
    {
        if (!$this->imageTricks->contains($imageTrick)) {
            $this->imageTricks[] = $imageTrick;
            $imageTrick->setTrick($this);
        }

        return $this;
    }

    public function removeImageTrick(ImageTrick $imageTrick): self
    {
        if ($this->imageTricks->removeElement($imageTrick)) {
            // set the owning side to null (unless already changed)
            if ($imageTrick->getTrick() === $this) {
                $imageTrick->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|VideoTrick[]
     */
    public function getVideoTricks(): Collection
    {
        return $this->videoTricks;
    }

    public function addVideoTrick(VideoTrick $videoTrick): self
    {
        if (!$this->videoTricks->contains($videoTrick)) {
            $this->videoTricks[] = $videoTrick;
            $videoTrick->setTrick($this);
        }

        return $this;
    }

    public function removeVideoTrick(VideoTrick $videoTrick): self
    {
        if ($this->videoTricks->removeElement($videoTrick)) {
            // set the owning side to null (unless already changed)
            if ($videoTrick->getTrick() === $this) {
                $videoTrick->setTrick(null);
            }
        }

        return $this;
    }

}
