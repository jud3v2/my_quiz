<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'quizz')]
    private ?User $user = null;

    #[ORM\OneToOne(mappedBy: 'categorie', cascade: ['persist', 'remove'])]
    private ?QuizzStats $quizzStats = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

        public function getOwner(): ?User
        {
                return $this->getUser();
        }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

        public function canDelete(): bool
        {
                return (bool) $this->getOwner();
        }

        public function getQuizzStats(): ?QuizzStats
        {
            return $this->quizzStats;
        }

        public function setQuizzStats(QuizzStats $quizzStats): static
        {
            // set the owning side of the relation if necessary
            if ($quizzStats->getCategorie() !== $this) {
                $quizzStats->setCategorie($this);
            }

            $this->quizzStats = $quizzStats;

            return $this;
        }
}
