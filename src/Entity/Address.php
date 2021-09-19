<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"address:read"}},
 *     denormalizationContext={"groups"={"address:write"}},
 *     attributes={
 *          "security"="is_granted('ROLE_USER')",
 *          "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *     },
 *     collectionOperations={
 *          "get"={},
 *          "post"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur"
 *         }
 *     },
 *     itemOperations={
 *         "get"={},
 *         "put"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur"
 *         },
 *         "patch"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur"
 *         },
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur"
 *         }
 *      },
 * )
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"address:read"})
     * @Groups({"client:read"})
     */
    private $id;

    /*/**
     * @ORM\Column(type="date")
     * @Groups({"address:read", "address:write"})
     * @Assert\NotBlank(message="Attention ce champ ne doit pas être vide")
     */
//    private $creationDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"address:read", "address:write"})
     * @Groups({"client:read", "client:write"})
     * @Assert\NotBlank(message="Attention ce champ ne doit pas être vide")
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=11)
     * @Groups({"address:read", "address:write"})
     * @Groups({"client:read", "client:write"})
     * @Assert\NotBlank(message="Attention ce champ ne doit pas être vide")
     */
    private $cp;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"address:read", "address:write"})
     * @Groups({"client:read", "client:write"})
     * @Assert\NotBlank(message="Attention ce champ ne doit pas être vide")
     */
    private $type;

    /*/**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="address", cascade={"persist", "remove"})
     */
    /*
    private $user;*/

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"client:read", "client:write"})
     * @Groups({"address:read", "address:write"})
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="address")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="addresses")
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    /*public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }*/

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(int $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /*public function getUser(): ?User
    {
        return $this->user;
    }*/

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
