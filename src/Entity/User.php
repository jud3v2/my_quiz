<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 180)]
        private string $email;

        #[ORM\Column(length: 180)]
        private string $username;

        #[ORM\Column(length: 180)]
        private string $firstname;

        #[ORM\Column(length: 180)]
        private string $lastname;

        #[ORM\Column]
        private \DateTime $createdAt;

        #[ORM\Column]
        private \DateTime $updatedAt;

        #[ORM\Column(length: 180)]
        private string $uuid;

        /**
         * @var list<string> The user roles
         */
        #[ORM\Column]
        private array $roles = [];

        /**
         * @var string The hashed password
         */
        #[ORM\Column]
        private ?string $password = null;

        #[ORM\Column]
        private ?bool $isVerified = false;

        /**
         * @var Collection<int, UserHistory>
         */
        #[ORM\OneToMany(targetEntity: UserHistory::class, mappedBy: 'user', orphanRemoval: true)]
        private Collection $userHistories;

        /**
         * @var Collection<int, UserReponse>
         */
        #[ORM\OneToMany(targetEntity: UserReponse::class, mappedBy: 'user', orphanRemoval: true)]
        private Collection $userReponses;

        /**
         * @var Collection<int, Categorie>
         */
        #[ORM\OneToMany(targetEntity: Categorie::class, mappedBy: 'user')]
        private Collection $quizz;

        #[ORM\Column(nullable: true)]
        private ?\DateTimeImmutable $last_connection = null;

        public function __construct()
        {
            $this->userHistories = new ArrayCollection();
            $this->userReponses = new ArrayCollection();
            $this->quizz = new ArrayCollection();
        }

        public function getId(): ?int
        {
                return $this->id;
        }

        public function getUuid(): ?string
        {
                return $this->uuid;
        }

        #[ORM\PrePersist, ORM\PreUpdate]
        public function updatedTimestamps(): void
        {
                $this->setUpdatedAt(new \DateTime('now'));
                if ($this->getCreatedAt() === null) {
                        $this->setCreatedAt(new \DateTime('now'));
                }
        }

        public function setUuid(string $uuid): static
        {
                $this->uuid = $uuid;

                return $this;
        }

        /**
         * A visual identifier that represents this user.
         *
         * @see UserInterface
         */
        public function getUserIdentifier(): string
        {
                return $this->uuid;
        }

        /**
         * @return list<string>
         * @see UserInterface
         *
         */
        public function getRoles(): array
        {
                $roles = $this->roles;
                // guarantee every user at least has ROLE_USER
                $roles[] = 'ROLE_USER';

                return array_unique($roles);
        }

        /**
         * @param list<string> $roles
         */
        public function setRoles(array $roles): static
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

        public function setPassword(string $password): static
        {
                $this->password = $password;

                return $this;
        }

        /**
         * @see UserInterface
         */
        public function eraseCredentials(): void
        {
                // If you store any temporary, sensitive data on the user, clear it here
                // $this->plainPassword = null;
        }

        public function getCreatedAt(): \DateTime
        {
                return $this->createdAt;
        }

        public function setCreatedAt(\DateTime $createdAt): void
        {
                $this->createdAt = $createdAt;
        }

        public function getUpdatedAt(): \DateTime
        {
                return $this->updatedAt;
        }

        public function setUpdatedAt(\DateTime $updatedAt): void
        {
                $this->updatedAt = $updatedAt;
        }

        public function getEmail(): string
        {
                return $this->email;
        }

        public function setEmail(string $email): void
        {
                $this->email = $email;
        }

        public function getUsername(): string
        {
                return $this->username;
        }

        public function setUsername(string $username): void
        {
                $this->username = $username;
        }

        public function getFirstname(): string
        {
                return $this->firstname;
        }

        public function setFirstname(string $firstname): void
        {
                $this->firstname = $firstname;
        }

        public function getLastname(): string
        {
                return $this->lastname;
        }

        public function setLastname(string $lastname): void
        {
                $this->lastname = $lastname;
        }

        public function getIsVerified(): ?bool
        {
                return $this->isVerified;
        }

        public function setIsVerified(?bool $isVerified): void
        {
                $this->isVerified = $isVerified;
        }

        public function isVerified(): bool
        {
            return $this->isVerified;
        }

        public function setVerified(bool $isVerified): static
        {
            $this->isVerified = $isVerified;

            return $this;
        }

        /**
         * @return Collection<int, UserHistory>
         */
        public function getUserHistories(): Collection
        {
            return $this->userHistories;
        }

        public function addUserHistory(UserHistory $userHistory): static
        {
            if (!$this->userHistories->contains($userHistory)) {
                $this->userHistories->add($userHistory);
                $userHistory->setUser($this);
            }

            return $this;
        }

        public function removeUserHistory(UserHistory $userHistory): static
        {
            if ($this->userHistories->removeElement($userHistory)) {
                // set the owning side to null (unless already changed)
                if ($userHistory->getUser() === $this) {
                    $userHistory->setUser(null);
                }
            }

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
                $userReponse->setUser($this);
            }

            return $this;
        }

        public function removeUserReponse(UserReponse $userReponse): static
        {
            if ($this->userReponses->removeElement($userReponse)) {
                // set the owning side to null (unless already changed)
                if ($userReponse->getUser() === $this) {
                    $userReponse->setUser(null);
                }
            }

            return $this;
        }

        /**
         * @return Collection<int, Categorie>
         */
        public function getQuizz(): Collection
        {
            return $this->quizz;
        }

        public function addQuizz(Categorie $quizz): static
        {
            if (!$this->quizz->contains($quizz)) {
                $this->quizz->add($quizz);
                $quizz->setUser($this);
            }

            return $this;
        }

        public function removeQuizz(Categorie $quizz): static
        {
            if ($this->quizz->removeElement($quizz)) {
                // set the owning side to null (unless already changed)
                if ($quizz->getUser() === $this) {
                    $quizz->setUser(null);
                }
            }

            return $this;
        }

        public function getLastConnection(): ?\DateTimeImmutable
        {
            return $this->last_connection;
        }

        public function setLastConnection(?\DateTimeImmutable $last_connection): static
        {
            $this->last_connection = $last_connection;

            return $this;
        }
}
