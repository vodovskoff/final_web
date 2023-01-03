<?php

namespace App\Entity;

use App\Repository\FineAndBonusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FineAndBonusRepository::class)
 */
class FineAndBonus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Manager::class, inversedBy="fineAndBonuses")
     */
    private $manager;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="date")
     */
    private $date_of_start;

    /**
     * @ORM\Column(type="date")
     */
    private $date_of_end;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDateOfStart(): ?\DateTimeInterface
    {
        return $this->date_of_start;
    }

    public function setDateOfStart(\DateTimeInterface $date_of_start): self
    {
        $this->date_of_start = $date_of_start;

        return $this;
    }

    public function getDateOfEnd(): ?\DateTimeInterface
    {
        return $this->date_of_end;
    }

    public function setDateOfEnd(\DateTimeInterface $date_of_end): self
    {
        $this->date_of_end = $date_of_end;

        return $this;
    }
}
