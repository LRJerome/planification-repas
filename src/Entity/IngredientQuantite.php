<?php

namespace App\Entity;

use App\Repository\IngredientQuantiteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientQuantiteRepository::class)]
#[ORM\Table(name: 'ingredient_quantite')]
class IngredientQuantite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Ingredient::class)]
    #[ORM\JoinColumn(name: 'ingredient_id', referencedColumnName: 'id', nullable: false)]
    private ?Ingredient $ingredient = null;

    #[ORM\ManyToOne(targetEntity: Repas::class, inversedBy: 'ingredientQuantites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Repas $repas = null;

    #[ORM\Column(type: 'float')]
    private ?float $quantite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;
        return $this;
    }

    public function getRepas(): ?Repas
    {
        return $this->repas;
    }

    public function setRepas(?Repas $repas): self
    {
        $this->repas = $repas;
        return $this;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }
}
