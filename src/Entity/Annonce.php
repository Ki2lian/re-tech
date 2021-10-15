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
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     * @Groups("data-wishlist")
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-wishlist")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     * @Groups("data-wishlist")
     */
    private $prix;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     * @Groups("data-wishlist")
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
     * @Groups("data-annonce")
     */
    private $id_compte;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="id_annonce")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     * @Groups("data-wishlist")
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="id_annonce", orphanRemoval=true)
     * @Groups("data-user")
     * @Groups("data-annonce")
     */
    private $messages;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     */
    private $actif;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
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

    public function __construct()
    {
        $this->liste_id_tag = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->wishlists = new ArrayCollection();
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

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setIdAnnonce($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getIdAnnonce() === $this) {
                $message->setIdAnnonce(null);
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
}
