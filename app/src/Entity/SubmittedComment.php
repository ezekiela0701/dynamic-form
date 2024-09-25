<?php

namespace App\Entity;

use App\Repository\SubmittedCommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubmittedCommentRepository::class)]
class SubmittedComment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?array $comment = null;

    #[ORM\ManyToOne(inversedBy: 'submittedComments')]
    private ?PageGenerate $pageGenerate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?array
    {
        return $this->comment;
    }

    public function setComment(?array $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPageGenerate(): ?PageGenerate
    {
        return $this->pageGenerate;
    }

    public function setPageGenerate(?PageGenerate $pageGenerate): static
    {
        $this->pageGenerate = $pageGenerate;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }
}
