<?php

namespace App\Controller;

use App\Entity\FineAndBonus;
use App\Entity\Manager;
use App\Entity\Pay;
use App\Repository\ActionRepository;
use App\Repository\FineAndBonusRepository;
use App\Repository\ManagerRepository;
use App\Repository\ManagerTimesheetRepository;
use App\Repository\PayRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PayController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="app_pay")
     */
    public function index(Request $request,
                          PayRepository $payRepository,
                          ManagerTimesheetRepository $managerTimesheetRepository,
                          FineAndBonusRepository $fineAndBonusRepository,
                          ManagerRepository $managerRepository, ActionRepository $actionRepository): Response
    {
        if ($request->get('month_of_payment')){
            $month = $request->get('month_of_payment');
            $_SESSION['month']=$month;
        } else {
            if (isset($_SESSION['month'])){
                $month = $_SESSION['month'];
            } else {
                $month = date('Y')."-".date('m');
            }
        }
        $minDays = null; $minSells=null; $minDrives=null; $maxSells=null; $maxDays=null; $maxDrives=null;
        $monthFormat = \DateTime::createFromFormat("Y-m", $month);
        $now = new \DateTime();
        $isThisCurrentMonth = false;
        $monthFormat->format('Y-m')==$now->format('Y-m') ? $isThisCurrentMonth = true : $isThisCurrentMonth = false;
        $pays = array();
        $areAllManagersCounted = true;
        $managers = $managerRepository->findAll();
        $refresh = new \DateTime();
        foreach ($managers as $manager) {
            if (!$payRepository->findOneByManagerAndMonth($manager->getId(), $monthFormat)) {
                $areAllManagersCounted = false;
            } else {
                $refresh = $payRepository->findOneByManagerAndMonth($manager->getId(), $monthFormat)->getRefreshDate();
            }
        }

        if (!$areAllManagersCounted or $request->get('refresh')) {
            $currentTime = new \DateTime();
            foreach ($managers as $manager) {
                $refresh = $this->refreshOrUpdatePay($manager, $payRepository, $actionRepository, $month, $currentTime, $fineAndBonusRepository, $managerTimesheetRepository);
            }
            $this->em->flush();
        }

        if (!$request->get('filter') and (!$request->get('premium') and !$request->get('fine'))) {
            foreach ($managers as $manager) {
                if ($payRepository->findOneByManagerAndMonth($manager->getId(), $monthFormat)) {
                    $pays[] = $payRepository->findOneByManagerAndMonthArray($manager->getId(), $monthFormat);
                }
            }
            foreach ($managers as $manager) {
                $currentTime = new \DateTime();
                $refresh = $this->refreshOrUpdatePay($manager, $payRepository, $actionRepository, $month, $currentTime, $fineAndBonusRepository, $managerTimesheetRepository);
            }
            $this->em->flush();
        } else {
            strlen($request->get('minDays')) > 0 ? $minDays = (int)$request->get('minDays') : $minDays = null;
            strlen($request->get('maxDays')) > 0 ? $maxDays = (int)$request->get('maxDays') : $maxDays = null;
            strlen($request->get('minSells')) > 0 ? $minSells = (int)$request->get('minSells') : $minSells = null;
            strlen($request->get('maxSells')) > 0 ? $maxSells = (int)$request->get('maxSells') : $maxSells = null;
            strlen($request->get('minDrives')) > 0 ? $minDrives = (int)$request->get('minDrives') : $minDrives = null;
            strlen($request->get('maxDrives')) > 0 ? $maxDrives = (int)$request->get('maxDrives') : $maxDrives = null;

            if (($request->get('fine') or $request->get('premium')) and $request->get('amount') and $request->get('fb_end')) {
                if ($request->get('fine')){
                    $amount = - (int) $request->get('amount');
                } else {
                    $amount = (int) $request->get('amount');
                }

                if(!$request->get('fine') and !$request->get('premium')){
                    $paysTemp = $payRepository->findObjecctsByFilters($monthFormat, $minDays, $maxDays, $minSells, $maxSells, $minDrives, $maxDrives);
                } else {
                    $paysTemp = $payRepository->findObjecctsByFilters($monthFormat, null, null, null, null, null, null);
                }

                foreach ($paysTemp as $pay){
                    $fb = new FineAndBonus();
                    $fb->setAmount($amount);
                    $fb->setManager($pay->getManager());
                    $fb->setDateOfStart(\DateTime::createFromFormat("Y-m-d",$request->get('fb_end')));
                    $fb->setDateOfEnd(\DateTime::createFromFormat("Y-m-d",$request->get('fb_end')));
                    $this->em->persist($fb);
                }
                $this->em->flush();
            } else {
                $paysTemp = $payRepository->findByFilters($monthFormat, $minDays, $maxDays, $minSells, $maxSells, $minDrives, $maxDrives);
                foreach ($paysTemp as $item) {
                    $pays[] = [0 => $item];
                }

            }
            if(count($pays)===0 and $request->get('filter')===null){
                foreach ($managers as $manager) {
                    if ($payRepository->findOneByManagerAndMonth($manager->getId(), $monthFormat)) {
                        $pays[] = $payRepository->findOneByManagerAndMonthArray($manager->getId(), $monthFormat);
                    }
                }
            }
        }

        $fios = array();
        $ids = array();
        foreach ($pays as $pay){
            $fios[$pay[0]['id']] = $payRepository->find($pay[0]['id'])->getManager()->getManagerName();
            $ids[$pay[0]['id']] = $payRepository->find($pay[0]['id'])->getManager()->getId();
        }


        return $this->render('pay/index.html.twig', [
            'controller_name' => 'PayController',
            'month' => $month,
            'refresh' => $refresh->format("Y.m.d"),
            'pays' => $pays,
            'fios' => $fios,
            'ids' => $ids,
            'isThisCurrentMonth' => $isThisCurrentMonth,
            'minDays' => $minDays, 'maxDays' => $maxDays, 'minSells' => $minSells, 'maxSells' => $maxSells, 'minDrives' => $minDrives, 'maxDrives' => $maxDrives,
        ]);
    }

    public function refreshOrUpdatePay(Manager $manager,
                                        PayRepository $payRepository,
                                        ActionRepository           $actionRepository,
                                        String                   $month,
                                        \DateTime                   $refresh_date,
                                        FineAndBonusRepository     $fineAndBonusRepository,
                                        ManagerTimesheetRepository $managerTimesheetRepository): \DateTime
    {
        $monthFormat = \DateTime::createFromFormat("Y-m", $month);
        $pays = array();
        $from = $monthFormat->format("Y-m-01");
        $to = $monthFormat->format("Y-m-t");

        $fromFormat = \DateTime::createFromFormat("Y-m-d", $from)->setTime(0 ,0);
        $toFormat = \DateTime::createFromFormat("Y-m-d", $to)->setTime(23, 59);
        $pay = $payRepository->findOneByManagerAndMonth($manager->getId(), $monthFormat);
        if($pay){
            $pay->refresh($actionRepository, $monthFormat, $refresh_date, $fineAndBonusRepository, $managerTimesheetRepository);
            $this->em->persist($pay);
        } else {
            $pay = new Pay();
            $pay->setManager($manager);
            $pay->setStartOfPeriod($fromFormat);
            $pay->setEndOfPeriod($toFormat);
            $pay->refresh($actionRepository, $monthFormat, $refresh_date, $fineAndBonusRepository, $managerTimesheetRepository);
            $this->em->persist($pay);
        }
        $this->em->flush();
        return $refresh_date;
    }
}
