<?php

namespace App\Entity;

use App\Repository\AppRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppRepository::class)]
class App
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $view = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getView(): ?int
    {
        return $this->view;
    }

    public function setView(int $view): static
    {
        $this->view = $view;

        return $this;
    }
}
