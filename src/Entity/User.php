<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}},
 *      attributes={
 *          "pagination_client_items_per_page"=false,
 *      },
 *      collectionOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_SUPERADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *              "openapi_context"={"summary"="hidden"},
 *          },
 *         "post"={
 *              "security"="is_granted('ROLE_SUPERADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *              "openapi_context"={"summary"="hidden"},
 *          },
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *              "openapi_context"={
 *                  "summary"="Get your own user account informations",
 *                  "description"="Here you can find all informations about your own user account registered on BileMo Rest Api",
 *              }
 *          },
 *          "put"={
 *              "security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur",
 *              "openapi_context"={
 *                  "summary"="Update your own user account informations",
 *                  "description"="Here you can Update your informations from your own user account registered on BileMo Rest Api",
 *                  "requestBody"={
 *                      "content"={
 *                          "application/ld+json"={
 *                              "schema"={
 *                                  "properties"={
 *                                      "email"={"type"="string", "example"="nom.prenom@mail.com"},
 *                                      "password"={"type"="string", "example"="myPassword"},
 *                                  }
 *                              }
 *                          }
 *                      }
 *                  }
 *              }
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_SUPERADMIN')",
 *              "security_message"="Attention, cette action nécéssite une élévation des droits utilisateur"
 *          }
 *      }
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *     "username": "partial",
 * })
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read", "user:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user:read", "user:write"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user:write"})

     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user:read", "user:write"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="user")
     * @Groups({"user:read", "user:write"})
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity=Client::class, mappedBy="user")
     * @Groups({"user:read", "user:write"})
     */
    private $client;

    public function __construct()
    {
        $this->client = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }

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
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
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
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClient(): Collection
    {
        return $this->client;
    }

    public function addClient(Client $client): self
    {
        if (!$this->client->contains($client)) {
            $this->client[] = $client;
            $client->setUser($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->client->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getUser() === $this) {
                $client->setUser(null);
            }
        }

        return $this;
    }
}
