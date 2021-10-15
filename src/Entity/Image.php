<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
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
     * @ORM\Column(type="boolean")
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     * @Groups("data-wishlist")
     */
    private $presentation;

    /**
     * @ORM\ManyToOne(targetEntity=Annonce::class, inversedBy="images")
     */
    private $id_annonce;

    /**
     * @ORM\Column(type="blob")
     */
    private $contenu;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("data-user")
     * @Groups("data-annonce")
     * @Groups("data-tag")
     */
    private $jeton;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPresentation(): ?bool
    {
        return $this->presentation;
    }

    public function setPresentation(bool $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getIdAnnonce(): ?Annonce
    {
        return $this->id_annonce;
    }

    public function setIdAnnonce(?Annonce $id_annonce): self
    {
        $this->id_annonce = $id_annonce;

        return $this;
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function setContenu($contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getJeton(): ?string
    {
        return $this->jeton;
    }

    public function setJeton(string $jeton): self
    {
        $this->jeton = $jeton;

        return $this;
    }
}
