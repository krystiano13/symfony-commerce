<?php

namespace App\Entity;

use App\Enums\OrderStatusEnum;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $surname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $town = null;

    #[ORM\Column(length: 6)]
    #[Assert\NotBlank]
    private ?string $postal_code = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $address = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[Assert\NotNull]
    private ?User $user_id = null;

    #[ORM\Column]
    #[Assert\Assert\Length(min: 0.01)]
    private ?float $full_price = null;

    /**
     * @var Collection<int, OrderedProduct>
     */
    #[ORM\OneToMany(targetEntity: OrderedProduct::class, mappedBy: 'order_id')]
    private Collection $orderedProducts;

    #[ORM\Column(enumType: OrderStatusEnum::class)]
    #[Assert\NotBlank]
    private ?OrderStatusEnum $status = null;

    #[ORM\Column(length: 9999)]
    private ?string $products = null;

    public function __construct()
    {
        $this->orderedProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): static
    {
        $this->town = $town;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): static
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getFullPrice(): ?float
    {
        return $this->full_price;
    }

    public function setFullPrice(float $full_price): static
    {
        $this->full_price = $full_price;

        return $this;
    }

    /**
     * @return Collection<int, OrderedProduct>
     */
    public function getOrderedProducts(): Collection
    {
        return $this->orderedProducts;
    }

    public function addOrderedProduct(OrderedProduct $orderedProduct): static
    {
        if (!$this->orderedProducts->contains($orderedProduct)) {
            $this->orderedProducts->add($orderedProduct);
            $orderedProduct->setOrderId($this);
        }

        return $this;
    }

    public function removeOrderedProduct(OrderedProduct $orderedProduct): static
    {
        if ($this->orderedProducts->removeElement($orderedProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderedProduct->getOrderId() === $this) {
                $orderedProduct->setOrderId(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?OrderStatusEnum
    {
        return $this->status;
    }

    public function setStatus(OrderStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getProducts(): ?string
    {
        return $this->products;
    }

    public function setProducts(string $products): static
    {
        $this->products = $products;

        return $this;
    }
}
