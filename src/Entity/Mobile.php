<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\MobileRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=MobileRepository::class)
 * @UniqueEntity(
 *     fields={"IMEI"},
 *     message="Attention, ce mobile existe déjà."
 * )
 * @ApiResource(
 *     denormalizationContext={
 *     "groups"={"mobile:write"},
 *     },
 *     normalizationContext={"groups"={"mobile:read"}},
 *     attributes={
 *          "pagination_client_items_per_page"=true,
 *          "pagination_items_per_page"=15
 *     },
 *     cacheHeaders={
 *          "max_age"=3600,
 *          "shared_max_age"=3600,
 *          "vary"={"Authorization"}
 *     },
 *     collectionOperations={
 *          "get"={"security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *                  "openapi_context"={
 *                      "summary"="Get all mobiles",
 *                      "description"="Get all mobiles available",
 *                   },
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_SUPERADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *              "openapi_context"={"summary"="hidden"},
 *          },
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "openapi_context"={
 *                      "summary"="Get one mobile",
 *                      "description"="Get one mobile from BileMo mobile list",
 *               },
 *         },
 *         "put"={
 *              "security"="is_granted('ROLE_SUPERADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *              "openapi_context"={"summary"="hidden"},
 *         },
 *         "delete"={
 *              "security"="is_granted('ROLE_SUPERADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *              "openapi_context"={"summary"="hidden"},
 *         },
 *      },
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *     "title": "partial",
 *     "brand.brand_name": "partial",
 *     "id": "exact",
 *     "IMEI": "exact",
 *     "model": "partial",
 *     "color": "exact",
 *     "price": "exact",
 *     "stockage": "exact",
 * })
 */
class Mobile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"mobile:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"mobile:read", "mobile:write"})
     * @Assert\Type(type="string", message="Attention le format est invalide")
     */
    private $title;

    /**
     * @ORM\Column(type="integer", unique=true)
     * @Groups({"mobile:read", "mobile:write"})
     * @Assert\NotBlank(message="Attention l'IMEI est obligatoire")
     * @Assert\Type(type="integer", message="Attention l'IMEI doit être un nombre")
     * @Assert\Length(
     *     min=8,
     *     max=10,
     *     minMessage="Attention, l'IMEI doit être composé d'au moins 8 caractères",
     *     maxMessage="Attention, l'IMEI ne peut être composé que de 10 caractères maximum"
     * )
     */
    private $IMEI;

    /**
     * @ORM\Column(type="text")
     * @Groups({"mobile:read", "mobile:write"}))
     * @Assert\Type(type="string", message="Attention le format est invalide")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"mobile:read", "mobile:write"})
     * @Assert\NotBlank(message="Attention vous devez ajouter un modèle")
     */
    private $model;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"mobile:read", "mobile:write"})
     * @Assert\NotBlank(message="Attention le prix est obligatoire")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"mobile:read", "mobile:write"})
     * @Assert\NotBlank(message="Attention la couleur est obligatoire")
     */
    private $color;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"mobile:read", "mobile:write"})
     * @Assert\NotBlank(message="Attention la capacité de stockage est obligatoire")
     */
    private $stockage;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="mobile")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"mobile:read", "mobile:write"})
     * @Assert\NotBlank(message="Attention la marque est obligatoire est obligatoire")
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"mobile:read", "mobile:write"})
     * @Assert\NotBlank(message="Attention l'état du mobile est obligatoire")
     */
    private $state;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"mobile:read", "mobile:write"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"mobile:read", "mobile:write"})
     */
    private $modifiedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIMEI(): ?int
    {
        return $this->IMEI;
    }

    public function setIMEI(int $IMEI): self
    {
        $this->IMEI = $IMEI;

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
    
    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getStockage(): ?int
    {
        return $this->stockage;
    }

    public function setStockage(int $stockage): self
    {
        $this->stockage = $stockage;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }
}
