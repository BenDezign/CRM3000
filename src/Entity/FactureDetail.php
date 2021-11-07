<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FactureDetailRepository")
 */
class FactureDetail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Facture", inversedBy="factureDetails")
     */
    private $Facture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Libelle;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $PU;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Qtt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ReferenceDet;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $MontantTva;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tva", inversedBy="factureDetails")
     */
    private $Tva;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacture(): ?Facture
    {
        return $this->Facture;
    }

    public function setFacture(?Facture $Facture): self
    {
        $this->Facture = $Facture;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(?string $Libelle): self
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    public function getPU(): ?float
    {
        return $this->PU;
    }

    public function setPU(?float $PU): self
    {
        $this->PU = $PU;

        return $this;
    }

    public function getQtt(): ?float
    {
        return $this->Qtt;
    }

    public function setQtt(?float $Qtt): self
    {
        $this->Qtt = $Qtt;

        return $this;
    }

    public function getReferenceDet(): ?string
    {
        return $this->ReferenceDet;
    }

    public function setReferenceDet(?string $ReferenceDet): self
    {
        $this->ReferenceDet = $ReferenceDet;

        return $this;
    }

    public function getMontantTva(): ?float
    {
        return $this->MontantTva;
    }

    public function setMontantTva(?float $MontantTva): self
    {
        $this->MontantTva = $MontantTva;

        return $this;
    }

    public function getTva(): ?Tva
    {
        return $this->Tva;
    }

    public function setTva(?Tva $Tva): self
    {
        $this->Tva = $Tva;

        return $this;
    }

}
