<?php
namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
#[ORM\Table(name: 'planning')]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: Repas::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Repas $petitDejeuner = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nombrePersonnesPetitDejeuner = null;

    #[ORM\ManyToOne(targetEntity: Repas::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Repas $encasMatin = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nombrePersonnesEncasMatin = null;

    #[ORM\ManyToOne(targetEntity: Repas::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Repas $dejeuner = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nombrePersonnesDejeuner = null;

    #[ORM\ManyToOne(targetEntity: Repas::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Repas $encasApresMidi = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nombrePersonnesEncasApresMidi = null;

    #[ORM\ManyToOne(targetEntity: Repas::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Repas $diner = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nombrePersonnesDiner = null;

    // Getters et setters pour chaque propriété

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPetitDejeuner(): ?Repas
    {
        return $this->petitDejeuner;
    }

    public function setPetitDejeuner(?Repas $petitDejeuner): self
    {
        $this->petitDejeuner = $petitDejeuner;

        return $this;
    }

    public function getEncasMatin(): ?Repas
    {
        return $this->encasMatin;
    }

    public function setEncasMatin(?Repas $encasMatin): self
    {
        $this->encasMatin = $encasMatin;

        return $this;
    }

    public function getDejeuner(): ?Repas
    {
        return $this->dejeuner;
    }

    public function setDejeuner(?Repas $dejeuner): self
    {
        $this->dejeuner = $dejeuner;

        return $this;
    }

    public function getEncasApresMidi(): ?Repas
    {
        return $this->encasApresMidi;
    }

    public function setEncasApresMidi(?Repas $encasApresMidi): self
    {
        $this->encasApresMidi = $encasApresMidi;

        return $this;
    }

    public function getDiner(): ?Repas
    {
        return $this->diner;
    }

    public function setDiner(?Repas $diner): self
    {
        $this->diner = $diner;

        return $this;
    }

    /**
     * Get the value of nombrePersonnes
    //  */ 
    // public function getNombrePersonnes()
    // {
    //     return $this->nombrePersonnes;
    // }

    // /**
    //  * Set the value of nombrePersonnes
    //  *
    //  * @return  self
    //  */ 
    // public function setNombrePersonnes($nombrePersonnes)
    // {
    //     $this->nombrePersonnes = $nombrePersonnes;

    //     return $this;
    // }

    /**
     * Get the value of nombrePersonnesPetitDejeuner
     */ 
    public function getNombrePersonnesPetitDejeuner()
    {
        return $this->nombrePersonnesPetitDejeuner;
    }

    /**
     * Set the value of nombrePersonnesPetitDejeuner
     *
     * @return  self
     */ 
    public function setNombrePersonnesPetitDejeuner($nombrePersonnesPetitDejeuner)
    {
        $this->nombrePersonnesPetitDejeuner = $nombrePersonnesPetitDejeuner;

        return $this;
    }

    /**
     * Get the value of nombrePersonnesEncasMatin
     */ 
    public function getNombrePersonnesEncasMatin()
    {
        return $this->nombrePersonnesEncasMatin;
    }

    /**
     * Set the value of nombrePersonnesEncasMatin
     *
     * @return  self
     */ 
    public function setNombrePersonnesEncasMatin($nombrePersonnesEncasMatin)
    {
        $this->nombrePersonnesEncasMatin = $nombrePersonnesEncasMatin;

        return $this;
    }

    /**
     * Get the value of nombrePersonnesDejeuner
     */ 
    public function getNombrePersonnesDejeuner()
    {
        return $this->nombrePersonnesDejeuner;
    }

    /**
     * Set the value of nombrePersonnesDejeuner
     *
     * @return  self
     */ 
    public function setNombrePersonnesDejeuner($nombrePersonnesDejeuner)
    {
        $this->nombrePersonnesDejeuner = $nombrePersonnesDejeuner;

        return $this;
    }

    /**
     * Get the value of nombrePersonnesEncasApresMidi
     */ 
    public function getNombrePersonnesEncasApresMidi()
    {
        return $this->nombrePersonnesEncasApresMidi;
    }

    /**
     * Set the value of nombrePersonnesEncasApresMidi
     *
     * @return  self
     */ 
    public function setNombrePersonnesEncasApresMidi($nombrePersonnesEncasApresMidi)
    {
        $this->nombrePersonnesEncasApresMidi = $nombrePersonnesEncasApresMidi;

        return $this;
    }

    /**
     * Get the value of nombrePersonnesDiner
     */ 
    public function getNombrePersonnesDiner()
    {
        return $this->nombrePersonnesDiner;
    }

    /**
     * Set the value of nombrePersonnesDiner
     *
     * @return  self
     */ 
    public function setNombrePersonnesDiner($nombrePersonnesDiner)
    {
        $this->nombrePersonnesDiner = $nombrePersonnesDiner;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
