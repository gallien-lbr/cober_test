<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $siren;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $l4_normalisee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $l6_normalisee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $l7_normalisee;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $l1_declaree;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $l2_declaree;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libtefen;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiren(): ?int
    {
        return $this->siren;
    }

    public function setSiren(int $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    public function getL4Normalisee(): ?string
    {
        return $this->l4_normalisee;
    }

    public function setL4Normalisee(?string $l4_normalisee): self
    {
        $this->l4_normalisee = $l4_normalisee;

        return $this;
    }

    public function getL6Normalisee(): ?string
    {
        return $this->l6_normalisee;
    }

    public function setL6Normalisee(?string $l6_normalisee): self
    {
        $this->l6_normalisee = $l6_normalisee;

        return $this;
    }

    public function getL7Normalisee(): ?string
    {
        return $this->l7_normalisee;
    }

    public function setL7Normalisee(?string $l7_normalisee): self
    {
        $this->l7_normalisee = $l7_normalisee;

        return $this;
    }

    public function getL1Declaree(): ?string
    {
        return $this->l1_declaree;
    }

    public function setL1Declaree(?string $l1_declaree): self
    {
        $this->l1_declaree = $l1_declaree;

        return $this;
    }

    public function getL2Declaree(): ?string
    {
        return $this->l2_declaree;
    }

    public function setL2Declaree(?string $l2_declaree): self
    {
        $this->l2_declaree = $l2_declaree;

        return $this;
    }

    public function getLibtefen(): ?string
    {
        return $this->libtefen;
    }

    public function setLibtefen(?string $libtefen): self
    {
        $this->libtefen = $libtefen;

        return $this;
    }
}
