<?php

namespace App\Entity;

use App\Repository\ManagerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ManagerRepository::class)
 */
class Manager
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
    private $manager_name;

    /**
     * @ORM\Column(type="float")
     */
    private $hourly_cost;

    /**
     * @ORM\Column(type="float")
     */
    private $percentage_comission;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $manager_passport_series;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $manager_passport_number;

    /**
     * @ORM\OneToMany(targetEntity=Action::class, mappedBy="manager")
     */
    private $actions;

    /**
     * @ORM\OneToMany(targetEntity=ManagerTimesheet::class, mappedBy="manager")
     */
    private $managerTimesheets;

    /**
     * @ORM\OneToMany(targetEntity=Pay::class, mappedBy="manager")
     */
    private $pays;

    /**
     * @ORM\OneToMany(targetEntity=FineAndBonus::class, mappedBy="manager")
     */
    private $fineAndBonuses;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
        $this->managerTimesheets = new ArrayCollection();
        $this->pays = new ArrayCollection();
        $this->fineAndBonuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManagerName(): ?string
    {
        return $this->manager_name;
    }

    public function setManagerName(string $manager_name): self
    {
        $this->manager_name = $manager_name;

        return $this;
    }

    public function getHourlyCost(): ?float
    {
        return $this->hourly_cost;
    }

    public function setHourlyCost(float $hourly_cost): self
    {
        $this->hourly_cost = $hourly_cost;

        return $this;
    }

    public function getPercentageComission(): ?float
    {
        return $this->percentage_comission;
    }

    public function setPercentageComission(float $percentage_comission): self
    {
        $this->percentage_comission = $percentage_comission;

        return $this;
    }

    public function getManagerPassportSeries(): ?string
    {
        return $this->manager_passport_series;
    }

    public function setManagerPassportSeries(string $manager_passport_series): self
    {
        $this->manager_passport_series = $manager_passport_series;

        return $this;
    }

    public function getManagerPassportNumber(): ?string
    {
        return $this->manager_passport_number;
    }

    public function setManagerPassportNumber(string $manager_passport_number): self
    {
        $this->manager_passport_number = $manager_passport_number;

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
            $action->setManager($this);
        }

        return $this;
    }

    public function removeAction(Action $action): self
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getManager() === $this) {
                $action->setManager(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ManagerTimesheet>
     */
    public function getManagerTimesheets(): Collection
    {
        return $this->managerTimesheets;
    }

    public function addManagerTimesheet(ManagerTimesheet $managerTimesheet): self
    {
        if (!$this->managerTimesheets->contains($managerTimesheet)) {
            $this->managerTimesheets[] = $managerTimesheet;
            $managerTimesheet->setManager($this);
        }

        return $this;
    }

    public function removeManagerTimesheet(ManagerTimesheet $managerTimesheet): self
    {
        if ($this->managerTimesheets->removeElement($managerTimesheet)) {
            // set the owning side to null (unless already changed)
            if ($managerTimesheet->getManager() === $this) {
                $managerTimesheet->setManager(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pay>
     */
    public function getPays(): Collection
    {
        return $this->pays;
    }

    public function addPay(Pay $pay): self
    {
        if (!$this->pays->contains($pay)) {
            $this->pays[] = $pay;
            $pay->setManager($this);
        }

        return $this;
    }

    public function removePay(Pay $pay): self
    {
        if ($this->pays->removeElement($pay)) {
            // set the owning side to null (unless already changed)
            if ($pay->getManager() === $this) {
                $pay->setManager(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FineAndBonus>
     */
    public function getFineAndBonuses(): Collection
    {
        return $this->fineAndBonuses;
    }

    public function addFineAndBonus(FineAndBonus $fineAndBonus): self
    {
        if (!$this->fineAndBonuses->contains($fineAndBonus)) {
            $this->fineAndBonuses[] = $fineAndBonus;
            $fineAndBonus->setManager($this);
        }

        return $this;
    }

    public function removeFineAndBonus(FineAndBonus $fineAndBonus): self
    {
        if ($this->fineAndBonuses->removeElement($fineAndBonus)) {
            // set the owning side to null (unless already changed)
            if ($fineAndBonus->getManager() === $this) {
                $fineAndBonus->setManager(null);
            }
        }

        return $this;
    }
}
