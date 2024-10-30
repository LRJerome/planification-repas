<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $quantiteDefaut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $unite = null;

    #[ORM\ManyToMany(targetEntity: Repas::class, mappedBy: 'ingredient')]
    private Collection $repas;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: ListeCourses::class, cascade: ['persist', 'remove'])]
    private Collection $listeCourses;

    public function __construct()
    {
        $this->repas = new ArrayCollection();
        $this->listeCourses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getQuantiteDefaut(): ?string
    {
        return $this->quantiteDefaut;
    }

    public function setQuantiteDefaut(?string $quantiteDefaut): static
    {
        $this->quantiteDefaut = $quantiteDefaut;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    public function getRepas(): Collection
    {
        return $this->repas;
    }

    public function addRepa(Repas $repa): static
    {
        if (!$this->repas->contains($repa)) {
            $this->repas->add($repa);
            $repa->addIngredient($this);
        }

        return $this;
    }

    public function removeRepa(Repas $repa): static
    {
        if ($this->repas->removeElement($repa)) {
            $repa->removeIngredient($this);
        }

        return $this;
    }

    public function getListeCourses(): Collection
    {
        return $this->listeCourses;
    }

    public function addListeCourse(ListeCourses $listeCourse): static
    {
        if (!$this->listeCourses->contains($listeCourse)) {
            $this->listeCourses->add($listeCourse);
            $listeCourse->setIngredient($this);
        }

        return $this;
    }

    public function removeListeCourse(ListeCourses $listeCourse): static
    {
        if ($this->listeCourses->removeElement($listeCourse)) {
            if ($listeCourse->getIngredient() === $this) {
                $listeCourse->setIngredient(null);
            }
        }

        return $this;
    }
}
