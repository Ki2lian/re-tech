<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("data-transaction")
     * @Groups("data-user")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("data-transaction")
     */
    private $id_compte;

    /**
     * @ORM\OneToOne(targetEntity=Annonce::class, inversedBy="transaction", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups("data-transaction")
     * @Groups("data-user")
     */
    private $id_annonce;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("data-transaction")
     * @Groups("data-user")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("data-user")
     */
    private $moyen_paiement;

    /**
     * @ORM\Column(type="boolean")
     * Si la transaction est une pub (0) ou si c'est une réservation cliente (1)
     */
    private $type;

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

    public function getIdAnnonce(): ?Annonce
    {
        return $this->id_annonce;
    }

    public function setIdAnnonce(Annonce $id_annonce): self
    {
        $this->id_annonce = $id_annonce;

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

    public function getMoyenPaiement(): ?string
    {
        return $this->moyen_paiement;
    }

    public function setMoyenPaiement(string $moyen_paiement): self
    {
        $this->moyen_paiement = $moyen_paiement;

        return $this;
    }

    public function getType(): ?bool
    {
        return $this->type;
    }

    public function setType(bool $type): self
    {
        $this->type = $type;

        return $this;
    }
}
