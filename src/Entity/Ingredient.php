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

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $quantiteDefaut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $unite = null;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: IngredientQuantite::class)]
    private Collection $ingredientQuantites;

    public function __construct()
    {
        $this->ingredientQuantites = new ArrayCollection();
        $this->quantiteDefaut = 1.0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getQuantiteDefaut(): ?float
    {
        return $this->quantiteDefaut;
    }

    public function setQuantiteDefaut(?float $quantiteDefaut): self
    {
        $this->quantiteDefaut = $quantiteDefaut;
        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): self
    {
        $this->unite = $unite;
        return $this;
    }

    /**
     * @return Collection<int, IngredientQuantite>
     */
    public function getIngredientQuantites(): Collection
    {
        return $this->ingredientQuantites;
    }

    public function addIngredientQuantite(IngredientQuantite $ingredientQuantite): self
    {
        if (!$this->ingredientQuantites->contains($ingredientQuantite)) {
            $this->ingredientQuantites->add($ingredientQuantite);
            $ingredientQuantite->setIngredient($this);
        }
        return $this;
    }

    public function removeIngredientQuantite(IngredientQuantite $ingredientQuantite): self
    {
        if ($this->ingredientQuantites->removeElement($ingredientQuantite)) {
            if ($ingredientQuantite->getIngredient() === $this) {
                $ingredientQuantite->setIngredient(null);
            }
        }
        return $this;
    }
}
