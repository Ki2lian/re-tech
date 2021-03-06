<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tags")
     * @Groups("data-tag")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tags")
     * @Groups("data-tag")
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity=Annonce::class, mappedBy="liste_id_tag")
     * @Groups("data-tag")
     */
    private $annonces;

    /**
     * @ORM\ManyToMany(targetEntity=WishlistSearch::class, mappedBy="liste_id_tag")
     */
    private $wishlistSearches;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->wishlistSearches = new ArrayCollection();
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

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->addListeIdTag($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            $annonce->removeListeIdTag($this);
        }

        return $this;
    }

    /**
     * @return Collection|WishlistSearch[]
     */
    public function getWishlistSearches(): Collection
    {
        return $this->wishlistSearches;
    }

    public function addWishlistSearch(WishlistSearch $wishlistSearch): self
    {
        if (!$this->wishlistSearches->contains($wishlistSearch)) {
            $this->wishlistSearches[] = $wishlistSearch;
            $wishlistSearch->addListeIdTag($this);
        }

        return $this;
    }

    public function removeWishlistSearch(WishlistSearch $wishlistSearch): self
    {
        if ($this->wishlistSearches->removeElement($wishlistSearch)) {
            $wishlistSearch->removeListeIdTag($this);
        }

        return $this;
    }

}
