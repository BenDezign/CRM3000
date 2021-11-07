<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $lastEmailAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isReplied;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isConsumed;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $status;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->isReplied = 0;
        $this->isConsumed = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getLastEmailAt(): ?\DateTimeImmutable
    {
        return $this->lastEmailAt;
    }

    public function setLastEmailAt(?\DateTimeImmutable $lastEmailAt): self
    {
        $this->lastEmailAt = $lastEmailAt;

        return $this;
    }

    public function getIsReplied(): ?bool
    {
        return $this->isReplied;
    }

    public function setIsReplied(bool $isReplied): self
    {
        $this->isReplied = $isReplied;

        return $this;
    }

    public function getIsConsumed(): ?bool
    {
        return $this->isConsumed;
    }

    public function setIsConsumed(?bool $isConsumed): self
    {
        $this->isConsumed = $isConsumed;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
