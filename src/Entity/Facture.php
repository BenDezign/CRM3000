<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FactureRepository")
 */
class Facture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FactureDetail", mappedBy="Facture", cascade={"persist"} , orphanRemoval=true)
     */
    private $factureDetails;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="factures")
     */
    private $company;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $reference;


    public function __construct()
    {
        $this->factureDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $factureDetail->setFacture($this);
        }

        return $this;
    }

    public function removeFactureDetail(FactureDetail $factureDetail): self
    {
        if ($this->factureDetails->contains($factureDetail)) {
            $this->factureDetails->removeElement($factureDetail);
            // set the owning side to null (unless already changed)
            if ($factureDetail->getFacture() === $this) {
                $factureDetail->setFacture(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }


}
