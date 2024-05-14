<?php

namespace App\Entity;

use App\Repository\UserHistoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserHistoryRepository::class)]
class UserHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $score = null;

    #[ORM\Column]
    private ?int $total = null;

    #[ORM\ManyToOne(inversedBy: 'userHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $quizz = null;

    /**
     * @var Collection<int, UserReponse>
     */
    #[ORM\OneToMany(targetEntity: UserReponse::class, mappedBy: 'history', orphanRemoval: true)]
    private Collection $userReponses;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct()
    {
        $this->userReponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getQuizz(): ?Categorie
    {
        return $this->quizz;
    }

    public function setQuizz(Categorie $quizz): static
    {
        $this->quizz = $quizz;

        return $this;
    }

    /**
     * @return Collection<int, UserReponse>
     */
    public function getUserReponses(): Collection
    {
        return $this->userReponses;
    }

    public function addUserReponse(UserReponse $userReponse): static
    {
        if (!$this->userReponses->contains($userReponse)) {
            $this->userReponses->add($userReponse);
            $userReponse->setHistory($this);
        }

        return $this;
    }

    public function removeUserReponse(UserReponse $userReponse): static
    {
        if ($this->userReponses->removeElement($userReponse)) {
            // set the owning side to null (unless already changed)
            if ($userReponse->getHistory() === $this) {
                $userReponse->setHistory(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
