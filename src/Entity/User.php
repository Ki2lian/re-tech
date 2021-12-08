<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity("email")
 * @UniqueEntity("pseudo")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    
    public function __construct()
    {
        $this->setRoles(['ROLE_USER']);
        $this->annonces = new ArrayCollection();
        $this->tickets = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->wishlists = new ArrayCollection();
        $this->wishlistSearches = new ArrayCollection();
        $this->conversationsSender = new ArrayCollection();
        $this->conversationsReceiver = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }
    

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-wishlist")
     * @Groups("data-transaction")
     * @Groups("data-conversation")
     */
    private $id;
   
    /**
     * @ORM\Column(type="string")
     * @Groups("data-user")
     * @Groups("data-tickets")
     * @Groups("data-wishlist")
     * @Groups("data-transaction")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups("data-user")
     * @Groups("data-wishlist")
     * @Groups("data-transaction")
     * @Groups("data-conversation")
     */
    protected $roles= [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("data-user")
     * @Groups("data-tickets")
     * @Groups("data-wishlist")
     * @Groups("data-transaction")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("data-user")
     * @Groups("data-tickets")
     * @Groups("data-wishlist")
     * @Groups("data-transaction")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=False)
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tickets")
     * @Groups("data-wishlist")
     * @Groups("data-transaction")
     * @Groups("data-conversation")
     */
    private $pseudo;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="id_compte", orphanRemoval=True)
     * @Groups("data-user")
     */
    private $annonces;

    /**
     * @ORM\OneToMany(targetEntity=Ticket::class, mappedBy="id_compte", orphanRemoval=true)
     * @Groups("data-user")
     */
    private $tickets;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("data-user")
     * @Groups("data-conversation")
     */
    private $actif;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("data-user")
     * @Groups("data-conversation")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("data-user")
     */
    private $date_modification;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("data-annonce")
     * @Groups("data-conversation")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="id_compte", orphanRemoval=true)
     * @Groups("data-user")
     */
    private $transactions;

    /**
     * @ORM\OneToMany(targetEntity=Wishlist::class, mappedBy="id_compte", orphanRemoval=true)
     * @Groups("data-user")
     */
    private $wishlists;

    /**
     * @ORM\OneToMany(targetEntity=WishlistSearch::class, mappedBy="id_compte")
     */
    private $wishlistSearches;

    /**
     * @ORM\OneToMany(targetEntity=Conversation::class, mappedBy="compte")
     */
    private $conversationsSender;

    /**
     * @ORM\OneToMany(targetEntity=Conversation::class, mappedBy="compte2")
     */
    private $conversationsReceiver;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="compte")
     */
    private $messages;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

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
            $annonce->setIdCompte($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getIdCompte() === $this) {
                $annonce->setIdCompte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setIdCompte($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getIdCompte() === $this) {
                $ticket->setIdCompte(null);
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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified)
    {
        $this->isVerified = $isVerified;
    }


    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setIdCompte($this);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getIdCompte() === $this) {
                $transaction->setIdCompte(null);
            }
        }

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
            $wishlist->setIdCompte($this);
        }

        return $this;
    }

    public function removeWishlist(Wishlist $wishlist): self
    {
        if ($this->wishlists->removeElement($wishlist)) {
            // set the owning side to null (unless already changed)
            if ($wishlist->getIdCompte() === $this) {
                $wishlist->setIdCompte(null);
            }
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
            $wishlistSearch->setIdCompte($this);
        }

        return $this;
    }

    public function removeWishlistSearch(WishlistSearch $wishlistSearch): self
    {
        if ($this->wishlistSearches->removeElement($wishlistSearch)) {
            // set the owning side to null (unless already changed)
            if ($wishlistSearch->getIdCompte() === $this) {
                $wishlistSearch->setIdCompte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversationsSender(): Collection
    {
        return $this->conversationsSender;
    }

    public function addConversationsSender(Conversation $conversationsSender): self
    {
        if (!$this->conversationsSender->contains($conversationsSender)) {
            $this->conversationsSender[] = $conversationsSender;
            $conversationsSender->setCompte($this);
        }

        return $this;
    }

    public function removeConversationsSender(Conversation $conversationsSender): self
    {
        if ($this->conversationsSender->removeElement($conversationsSender)) {
            // set the owning side to null (unless already changed)
            if ($conversationsSender->getCompte() === $this) {
                $conversationsSender->setCompte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getConversationsReceiver(): Collection
    {
        return $this->conversationsReceiver;
    }

    public function addConversationsReceiver(Conversation $conversationsReceiver): self
    {
        if (!$this->conversationsReceiver->contains($conversationsReceiver)) {
            $this->conversationsReceiver[] = $conversationsReceiver;
            $conversationsReceiver->setCompte2($this);
        }

        return $this;
    }

    public function removeConversationsReceiver(Conversation $conversationsReceiver): self
    {
        if ($this->conversationsReceiver->removeElement($conversationsReceiver)) {
            // set the owning side to null (unless already changed)
            if ($conversationsReceiver->getCompte2() === $this) {
                $conversationsReceiver->setCompte2(null);
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
            $message->setCompte($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getCompte() === $this) {
                $message->setCompte(null);
            }
        }

        return $this;
    }

   
}
