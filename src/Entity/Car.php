<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Brand;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Model;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Color;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="Car")
     */
    private $car;

    public function __construct()
    {
        $this->car = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->Brand;
    }

    public function setBrand(string $Brand): self
    {
        $this->Brand = $Brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->Model;
    }

    public function setModel(string $Model): self
    {
        $this->Model = $Model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->Color;
    }

    public function setColor(string $Color): self
    {
        $this->Color = $Color;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getCar(): Collection
    {
        return $this->car;
    }

    public function addCar(Product $car): self
    {
        if (!$this->car->contains($car)) {
            $this->car[] = $car;
            $car->setCar($this);
        }

        return $this;
    }

    public function removeCar(Product $car): self
    {
        if ($this->car->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getCar() === $this) {
                $car->setCar(null);
            }
        }

        return $this;
    }
}
