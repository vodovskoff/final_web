<?php

namespace App\Entity;

use App\Repository\ManagerTimesheetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ManagerTimesheetRepository::class)
 */
class ManagerTimesheet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Manager::class, inversedBy="managerTimesheets")
     */
    private $manager;

    /**
     * @ORM\Column(type="datetime")
     */
    private $coming_to_work;

    /**
     * @ORM\Column(type="datetime")
     */
    private $leaving_from_work;

    /**
     * @ORM\Column(type="date")
     */
    private $sheduled_date;

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

    public function getComingToWork(): ?\DateTimeInterface
    {
        return $this->coming_to_work;
    }

    public function setComingToWork(\DateTimeInterface $coming_to_work): self
    {
        $this->coming_to_work = $coming_to_work;

        return $this;
    }

    public function getLeavingFromWork(): ?\DateTimeInterface
    {
        return $this->leaving_from_work;
    }

    public function setLeavingFromWork(\DateTimeInterface $leaving_from_work): self
    {
        $this->leaving_from_work = $leaving_from_work;

        return $this;
    }

    public function getSheduledDate(): ?\DateTimeInterface
    {
        return $this->sheduled_date;
    }

    public function setSheduledDate(\DateTimeInterface $sheduled_date): self
    {
        $this->sheduled_date = $sheduled_date;

        return $this;
    }
}
