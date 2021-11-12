<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TicketRepository::class)
 */
class Ticket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("data-user")
     * @Groups("data-tickets")
     */
    private $id;

    /**
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE" )
     * @ORM\JoinColumn(nullable=false)
     * @Groups("data-tickets")
     */
    private $id_compte;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("data-user")
     * @Groups("data-tickets")
     */
    private $actif;

    /**
     * @ORM\Column(type="text")
     * @Groups("data-user")
     * @Groups("data-tickets")
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("data-user")
     * @Groups("data-tickets")
     */
    private $date_creation;

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

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

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
}
