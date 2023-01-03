<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use App\Repository\FineAndBonusRepository;
use App\Repository\ManagerTimesheetRepository;
use App\Repository\PayRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass=PayRepository::class)
 */
class Pay
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $start_of_period;

    /**
     * @ORM\Column(type="date")
     */
    private $end_of_period;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $refresh_date;

    /**
     * @ORM\ManyToOne(targetEntity=Manager::class, inversedBy="pays")
     */
    private $manager;

    /**
     * @ORM\Column(type="integer")
     */
    private $working_days;

    /**
     * @ORM\Column(type="integer")
     */
    private $sells_number;

    /**
     * @ORM\Column(type="integer")
     */
    private $consultations;

    /**
     * @ORM\Column(type="integer")
     */
    private $test_drives;

    /**
     * @ORM\Column(type="float")
     */
    private $fine;

    /**
     * @ORM\Column(type="float")
     */
    private $premiuim;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartOfPeriod(): ?\DateTimeInterface
    {
        return $this->start_of_period;
    }

    public function setStartOfPeriod(\DateTimeInterface $start_of_period): self
    {
        $this->start_of_period = $start_of_period;

        return $this;
    }

    public function getEndOfPeriod(): ?\DateTimeInterface
    {
        return $this->end_of_period;
    }

    public function setEndOfPeriod(\DateTimeInterface $end_of_period): self
    {
        $this->end_of_period = $end_of_period;

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

    public function getRefreshDate(): ?\DateTimeInterface
    {
        return $this->refresh_date;
    }

    public function setRefreshDate(\DateTimeInterface $refresh_date): self
    {
        $this->refresh_date = $refresh_date;

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

    public function getWorkingDays(): ?int
    {
        return $this->working_days;
    }

    public function setWorkingDays(int $working_days): self
    {
        $this->working_days = $working_days;

        return $this;
    }

    public function getSellsNumber(): ?int
    {
        return $this->sells_number;
    }

    public function setSellsNumber(int $sells_number): self
    {
        $this->sells_number = $sells_number;

        return $this;
    }

    public function getConsultations(): ?int
    {
        return $this->consultations;
    }

    public function setConsultations(int $consultations): self
    {
        $this->consultations = $consultations;

        return $this;
    }

    public function getTestDrives(): ?int
    {
        return $this->test_drives;
    }

    public function setTestDrives(int $test_drives): self
    {
        $this->test_drives = $test_drives;

        return $this;
    }

    public function refresh(ActionRepository           $actionRepository,
                            \DateTime                   $month,
                            \DateTime                   $refresh_date,
                            FineAndBonusRepository     $fineAndBonusRepository,
                            ManagerTimesheetRepository $managerTimesheetRepository): self
    {
        $this->setConsultations($actionRepository->countActionsByTypeAndUser($this->getManager()->getId(),3, $month));
        $this->setRefreshDate($refresh_date);
        $this->setTestDrives($actionRepository->countActionsByTypeAndUser($this->getManager()->getId(), 1, $month));
        $this->setSellsNumber($actionRepository->countActionsByTypeAndUser($this->getManager()->getId(), 2, $month));
        $this->setWorkingDays($managerTimesheetRepository->countWorkingDays($this->getManager()->getId(), $month));
        $this->setFine($fineAndBonusRepository->findAmountByManagerAndMonth($this->getManager()->getId(), $month, -1));
        $this->setPremiuim($fineAndBonusRepository->findAmountByManagerAndMonth($this->getManager()->getId(), $month, 1));
        $amount = $this->getManager()->getHourlyCost()*8*$this->getWorkingDays() + $this->getConsultations()*200;
        $sells = $actionRepository->getActionsByTypeAndManager($this->getManager()->getId(), 2, $month);
        foreach ($sells as $sell){
            $amount = $amount + $sell->getProduct()->getProductPrice()*$this->getManager()->getPercentageComission()/100;
        }
        $this->setAmount($amount);
        return $this;
    }

    public function getFine(): ?float
    {
        return $this->fine;
    }

    public function setFine(float $fine): self
    {
        $this->fine = $fine;

        return $this;
    }

    public function getPremiuim(): ?float
    {
        return $this->premiuim;
    }

    public function setPremiuim(float $premiuim): self
    {
        $this->premiuim = $premiuim;

        return $this;
    }
}
