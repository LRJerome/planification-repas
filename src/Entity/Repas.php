<?php

namespace App\Entity;

use App\Repository\RepasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: RepasRepository::class)]
#[Vich\Uploadable]
class Repas
{
    public const CATEGORIE_LOW_CARB = 'low-carb';
    public const CATEGORIE_POST_TRAINING = 'post-training';
    public const CATEGORIE_EN_CAS = 'en-cas';
    public const CATEGORIE_PETIT_DEJEUNER = 'petit-dejeuner';

    public const CATEGORIES = [
        'low-carb' => 'Low-carb',
        'post-training' => 'Post-training',
        'en-cas' => 'En-cas',
        'petit-dejeuner' => 'Petit déjeuner',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La catégorie est obligatoire")]
    private ?string $categorie = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "La recette est obligatoire")]
    private ?string $recette = null;

    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'repas')]
    private Collection $ingredients;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeRepas = null;

    #[ORM\OneToMany(mappedBy: 'repas', targetEntity: IngredientQuantite::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Assert\Count(min: 1, minMessage: "Vous devez ajouter au moins un ingrédient")]
    private Collection $ingredientQuantites;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[Vich\UploadableField(mapping: 'repas_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->ingredientQuantites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $categorie = strtolower(str_replace([' ', 'é'], ['-', 'e'], $categorie));
        $this->categorie = $categorie;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getRecette(): ?string
    {
        return $this->recette;
    }

    public function setRecette(?string $recette): self
    {
        $this->recette = $recette;
        return $this;
    }

    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }
        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getTypeRepas(): ?string
    {
        return $this->typeRepas;
    }

    public function setTypeRepas(?string $typeRepas): self
    {
        $this->typeRepas = $typeRepas;
        return $this;
    }

    public function getIngredientQuantites(): Collection
    {
        return $this->ingredientQuantites;
    }

    public function addIngredientQuantite(IngredientQuantite $ingredientQuantite): self
    {
        if (!$this->ingredientQuantites->contains($ingredientQuantite)) {
            $this->ingredientQuantites->add($ingredientQuantite);
            $ingredientQuantite->setRepas($this);
        }
        return $this;
    }

    public function removeIngredientQuantite(IngredientQuantite $ingredientQuantite): self
    {
        if ($this->ingredientQuantites->removeElement($ingredientQuantite)) {
            if ($ingredientQuantite->getRepas() === $this) {
                $ingredientQuantite->setRepas(null);
            }
        }
        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        if (null === $imageFile && $this->imageName) {
            $this->imageName = null;
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
}