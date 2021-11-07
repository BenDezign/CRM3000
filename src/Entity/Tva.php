<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TvaRepository")
 */
class Tva
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Taux;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Tva;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FactureDetail", mappedBy="Tva")
     */
    private $factureDetails;


    public function __construct()
    {
        $this->factureDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaux(): ?float
    {
        return $this->Taux;
    }

    public function setTaux(?float $Taux): self
    {
        $this->Taux = $Taux;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->Tva;
    }

    public function setTva(?float $Tva): self
    {
        $this->Tva = $Tva;

        return $this;
    }

    /**
     * @return Collection|FactureDetail[]
     */
    public function getFactureDetails(): Collection
    {
        return $this->factureDetails;
    }

    public function addFactureDetail(FactureDetail $factureDetail): self
    {
        if (!$this->factureDetails->contains($factureDetail)) {
            $this->factureDetails[] = $factureDetail;
            $factureDetail->setTva($this);
        }

        return $this;
    }

    public function removeFactureDetail(FactureDetail $factureDetail): self
    {
        if ($this->factureDetails->contains($factureDetail)) {
            $this->factureDetails->removeElement($factureDetail);
            // set the owning side to null (unless already changed)
            if ($factureDetail->getTva() === $this) {
                $factureDetail->setTva(null);
            }
        }

        return $this;
    }

}
