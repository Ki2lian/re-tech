<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 */
class Annonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     * @Groups("data-wishlist")
     * @Groups("data-transaction")
     * @Groups("data-annonce-search")
     * @Groups("data-conversation")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     * @Groups("data-wishlist")
     * @Groups("data-transaction")
     * @Groups("data-annonce-search")
     * @Groups("data-conversation")
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-wishlist")
     * @Groups("data-transaction")
     * @Groups("data-conversation")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     * @Groups("data-wishlist")
     * @Groups("data-transaction")
     * @Groups("data-conversation")
     */
    private $prix;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     * @Groups("data-wishlist")
     * @Groups("data-transaction")
     * @Groups("data-conversation")
     */
    private $annonce_payante;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="annonces")
     * @Groups("data-user")
     * @Groups("data-annonce")
     */
    private $liste_id_tag;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="annonces")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     * @Groups("data-annonce")
     * @Groups("data-transaction")
     */
    private $id_compte;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="id_annonce",cascade={"persist"})
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     * @Groups("data-wishlist")
     * @Groups("data-transaction")
     * @Groups("data-annonce-search")
     * @Groups("data-conversation")
     */
    private $images;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     * @Groups("data-conversation")
     */
    private $actif;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     * @Groups("data-conversation")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     * @Groups("data-conversation")
     */
    private $date_modification;

    /**
     * @ORM\OneToOne(targetEntity=Transaction::class, mappedBy="id_annonce", cascade={"persist", "remove"})
     */
    private $transaction;

    /**
     * @ORM\OneToMany(targetEntity=Wishlist::class, mappedBy="id_annonce", orphanRemoval=true)
     */
    private $wishlists;

    /**
     * @ORM\OneToMany(targetEntity=Conversation::class, mappedBy="annonce")
     */
    private $conversations;

    public function __construct()
    {
        $this->liste_id_tag = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->wishlists = new ArrayCollection();
        $this->conversations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getAnnoncePayante(): ?bool
    {
        return $this->annonce_payante;
    }

    public function setAnnoncePayante(bool $annonce_payante): self
    {
        $this->annonce_payante = $annonce_payante;

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
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setIdAnnonce($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getIdAnnonce() === $this) {
                $image->setIdAnnonce(null);
            }
        }

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->date_modification;
    }

    public function setDateModification(\DateTimeInterface $date_modification): self
    {
        $this->date_modification = $date_modification;

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(Transaction $transaction): self
    {
        // set the owning side of the relation if necessary
        if ($transaction->getIdAnnonce() !== $this) {
            $transaction->setIdAnnonce($this);
        }

        $this->transaction = $transaction;

        return $this;
    }

    /**
     * @return Collection|Wishlist[]
     */
    public function getWishlists(): Collection
    {
        return $this->wishlists;
    }

    public function addWishlist(Wishlist $wishlist): self
    {
        if (!$this->wishlists->contains($wishlist)) {
            $this->wishlists[] = $wishlist;
            $wishlist->setIdAnnonce($this);
        }

        return $this;
    }

    public function removeWishlist(Wishlist $wishlist): self
    {
        if ($this->wishlists->removeElement($wishlist)) {
            // set the owning side to null (unless already changed)
            if ($wishlist->getIdAnnonce() === $this) {
                $wishlist->setIdAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->setAnnonce($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getAnnonce() === $this) {
                $conversation->setAnnonce(null);
            }
        }

        return $this;
    }
}
