<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 * @ApiResource(
 *     denormalizationContext={
 *          "groups"={"brand:write"},
 *     },
 *     normalizationContext={"groups"={"brand:read"}},
 *     attributes={
 *          "pagination_client_items_per_page"=false,
 *     },
 *     cacheHeaders={
 *          "max_age"=3600,
 *          "shared_max_age"=3600,
 *          "vary"={"Authorization"}
 *     },
 *     collectionOperations={
 *          "get"={"security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "openapi_context"={
 *                      "summary"="Get all mobile's brand",
 *                      "description"="Get all mobile's brand available",
 *               },
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_SUPERADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *              "openapi_context"={"summary"="hidden"},
 *         }
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
 *              "openapi_context"={
 *                      "summary"="Get one mobile's brand",
 *                      "description"="Get one mobile's brand",
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
 *         }
 *      },
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *     "brand_name": "partial",
 *     "id": "exact",
 * })
 */
class Brand
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"brand:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brand:read", "brand:write"})
     */
    private $brand_name;

    /**
     * @ORM\OneToMany(targetEntity=Mobile::class, mappedBy="brand", cascade={"persist", "remove"})
     * @Groups({"brand:read", "brand:write"})
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
