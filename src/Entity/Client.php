<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ApiResource(
 *     denormalizationContext={"groups"={"client:write"}},
 *     normalizationContext={"groups"={"client:read"}},
 *     cacheHeaders={
 *          "max_age"=3600,
 *          "shared_max_age"=3600,
 *          "vary"={"Authorization"}
 *     },
 *     attributes={
 *          "pagination_client_items_per_page"=true,
 *          "pagination_items_per_page"=20
 *     },
 *     collectionOperations={
 *          "get"={"security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *              "openapi_context"={
 *                  "summary"="Get all clients related to your user account",
 *                  "description"="Here you can find all the clients related to your own account, no access to other user client's list is possible",
 *              }
 *          },
 *          "post"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *              "openapi_context"={
 *                  "summary"="Add a new client to your own client list",
 *                  "requestBody"={
 *                      "content"={
 *                          "application/ld+json"={
 *                              "schema"={
 *                                  "properties"={
 *                                      "email"={"type"="string", "example"="nom.prenom@mail.com"}
 *                                  }
 *                              }
 *                          }
 *                      }
 *                  }
 *              }
 *         }
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN')",
 *         "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *         "openapi_context"={
 *                  "summary"="Get one client related to your user account",
 *                  "description"="Here you can find one client related to your own account, no access to other user client's is possible",
 *              },
 *          },
 *         "put"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *              "openapi_context"={
 *                  "summary"="Update one client file related to your user account",
 *                  "description"="Here you can update one client related to your own account, no access to other user client's is possible",
 *              },
 *         },
 *         "delete"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *              "openapi_context"={
 *                  "summary"="Delete a client file related to your user account",
 *                  "description"="Here you can delete a client related to your own account, no delete action is possible on other user client's list",
 *              }
 *         }
 *      },
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *     "id": "exact",
 *     "name": "partial",
 *     "address.city": "partial",
 *     "email": "partial",
 *     "user.id": "exact",
 * })
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"client:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"client:read", "client:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"client:read", "client:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"client:read"})
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="client")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"client:read", "client:write"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="client", cascade={"persist", "remove"})
     * @Groups({"client:read", "client:write"})
     */
    private $addresses;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

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

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setClient($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getClient() === $this) {
                $address->setClient(null);
            }
        }

        return $this;
    }
}
