<?php

namespace App\Entity;

//use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\MobileRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=MobileRepository::class)
 * @ApiResource(
 *  normalizationContext={"groups"={"mobile_read", "invoice_read"}}
 * )
 */
class Mobile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"mobile_read", "invoice_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"mobile_read", "invoice_read"})
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"mobile_read", "invoice_read"})
     */
    private $IMEI;

    /**
     * @ORM\Column(type="text")
     * @Groups({"mobile_read", "invoice_read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"mobile_read", "invoice_read"})
     */
    private $model;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"mobile_read", "invoice_read"})
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"mobile_read", "invoice_read"})
     */
    private $color;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"mobile_read", "invoice_read"})
     */
    private $stockage;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="mobile")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"mobile_read", "invoice_read"})
     */
    private $state;

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
}
