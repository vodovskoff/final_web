<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $availability_number;

    /**
     * @ORM\Column(type="integer")
     */
    private $year_production;

    /**
     * @ORM\ManyToOne(targetEntity=Car::class, inversedBy="car")
     */
    private $Car;

    /**
     * @ORM\OneToMany(targetEntity=Action::class, mappedBy="product")
     */
    private $actions;

    /**
     * @ORM\Column(type="float")
     */
    private $product_price;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvailabilityNumber(): ?int
    {
        return $this->availability_number;
    }

    public function setAvailabilityNumber(int $availability_number): self
    {
        $this->availability_number = $availability_number;

        return $this;
    }

    public function getYearProduction(): ?int
    {
        return $this->year_production;
    }

    public function setYearProduction(int $year_production): self
    {
        $this->year_production = $year_production;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->Car;
    }

    public function setCar(?Car $Car): self
    {
        $this->Car = $Car;

        return $this;
    }

    /**
     * @return Collection<int, Action>
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(Action $action): self
    {
        if (!$this->actions->contains($action)) {
            $this->actions[] = $action;
            $action->setProduct($this);
        }

        return $this;
    }

    public function removeAction(Action $action): self
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getProduct() === $this) {
                $action->setProduct(null);
            }
        }

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->product_price;
    }

    public function setProductPrice(float $product_price): self
    {
        $this->product_price = $product_price;

        return $this;
    }
}
