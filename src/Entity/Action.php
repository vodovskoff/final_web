<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActionRepository::class)
 */
class Action
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Buyer::class, inversedBy="actions")
     */
    private $buyer;

    /**
     * @ORM\ManyToOne(targetEntity=Manager::class, inversedBy="actions")
     */
    private $manager;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="actions")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=ActionType::class, inversedBy="actions")
     */
    private $action_type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getBuyer(): ?Buyer
    {
        return $this->buyer;
    }

    public function setBuyer(?Buyer $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    public function setManager(?Manager $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getActionType(): ?ActionType
    {
        return $this->action_type;
    }

    public function setActionType(?ActionType $action_type): self
    {
        $this->action_type = $action_type;

        return $this;
    }
}
