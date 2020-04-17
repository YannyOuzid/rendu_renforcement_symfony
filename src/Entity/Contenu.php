<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContenuRepository")
 */
class Contenu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\produit", inversedBy="contenus")
     */
    private $produit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\panier", mappedBy="contenu")
     */
    private $panier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    public function __construct()
    {
        $this->panier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?produit
    {
        return $this->produit;
    }

    public function setProduit(?produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * @return Collection|panier[]
     */
    public function getPanier(): Collection
    {
        return $this->panier;
    }

    public function addPanier(panier $panier): self
    {
        if (!$this->panier->contains($panier)) {
            $this->panier[] = $panier;
            $panier->setContenu($this);
        }

        return $this;
    }

    public function removePanier(panier $panier): self
    {
        if ($this->panier->contains($panier)) {
            $this->panier->removeElement($panier);
            // set the owning side to null (unless already changed)
            if ($panier->getContenu() === $this) {
                $panier->setContenu(null);
            }
        }

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
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
}
