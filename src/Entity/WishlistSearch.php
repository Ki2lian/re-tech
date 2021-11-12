<?php

namespace App\Entity;

use App\Repository\WishlistSearchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WishlistSearchRepository::class)
 */
class WishlistSearch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="wishlistSearches")
     */
    private $id_compte;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="wishlistSearches")
     */
    private $liste_id_tag;

    public function __construct()
    {
        $this->liste_id_tag = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCompte(): ?User
    {
        return $this->id_compte;
    }

    public function setIdCompte(?User $id_compte): self
    {
        $this->id_compte = $id_compte;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getListeIdTag(): Collection
    {
        return $this->liste_id_tag;
    }

    public function addListeIdTag(Tag $listeIdTag): self
    {
        if (!$this->liste_id_tag->contains($listeIdTag)) {
            $this->liste_id_tag[] = $listeIdTag;
        }

        return $this;
    }

    public function removeListeIdTag(Tag $listeIdTag): self
    {
        $this->liste_id_tag->removeElement($listeIdTag);

        return $this;
    }
}
