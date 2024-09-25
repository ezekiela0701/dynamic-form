<?php

namespace App\Entity;

use App\Repository\PageGenerateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageGenerateRepository::class)]
class PageGenerate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $fields = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, SubmittedComment>
     */
    #[ORM\OneToMany(targetEntity: SubmittedComment::class, mappedBy: 'pageGenerate')]
    private Collection $submittedComments;

    public function __construct()
    {
        $this->submittedComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getFields(): ?string
    {
        return $this->fields;
    }

    public function setFields(string $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, SubmittedComment>
     */
    public function getSubmittedComments(): Collection
    {
        return $this->submittedComments;
    }

    public function addSubmittedComment(SubmittedComment $submittedComment): static
    {
        if (!$this->submittedComments->contains($submittedComment)) {
            $this->submittedComments->add($submittedComment);
            $submittedComment->setPageGenerate($this);
        }

        return $this;
    }

    public function removeSubmittedComment(SubmittedComment $submittedComment): static
    {
        if ($this->submittedComments->removeElement($submittedComment)) {
            // set the owning side to null (unless already changed)
            if ($submittedComment->getPageGenerate() === $this) {
                $submittedComment->setPageGenerate(null);
            }
        }

        return $this;
    }
}
