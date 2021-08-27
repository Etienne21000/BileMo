<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 * @ApiResource
 */
class Brand
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"mobile:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"mobile:read"})
     */
    private $brand_name;

    /**
     * @ORM\OneToMany(targetEntity=Mobile::class, mappedBy="brand", cascade={"persist", "remove"})
     */
    private $mobile;

    public function __construct()
    {
        $this->mobile = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrandName(): ?string
    {
        return $this->brand_name;
    }

    public function setBrandName(string $brand_name): self
    {
        $this->brand_name = $brand_name;

        return $this;
    }

    /**
     * @return Collection|Mobile[]
     */
    public function getMobile(): Collection
    {
        return $this->mobile;
    }

    public function addMobile(Mobile $mobile): self
    {
        if (!$this->mobile->contains($mobile)) {
            $this->mobile[] = $mobile;
            $mobile->setBrand($this);
        }

        return $this;
    }

    public function removeMobile(Mobile $mobile): self
    {
        if ($this->mobile->removeElement($mobile)) {
            // set the owning side to null (unless already changed)
            if ($mobile->getBrand() === $this) {
                $mobile->setBrand(null);
            }
        }

        return $this;
    }
}
