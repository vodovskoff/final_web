<?php

namespace App\Controller;

use App\Entity\FineAndBonus;
use App\Repository\ActionRepository;
use App\Repository\ActionTypeRepository;
use App\Repository\FineAndBonusRepository;
use App\Repository\ManagerRepository;
use App\Repository\ManagerTimesheetRepository;
use App\Repository\PayRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ManagerController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/manager", name="app_manager")
     */
    public function index(PayController $payController,
                          ManagerTimesheetRepository $managerTimesheetRepository,
                          Request $request,
                          PayRepository $payRepository,
                          ActionRepository $actionRepository,
                          FineAndBonusRepository $fineAndBonusRepository,
                          ActionTypeRepository $actionTypeRepository,
                          ManagerRepository $managerRepository
    ): Response
    {
        if(session_id() === "") session_start();
        $managers_id = array();
        $start_month = "";
        $last_month = "";
        $managers = array();

        if ($request->get('ids')){
            $managers_id = $request->get('ids');
            $_SESSION['ids'] = $managers_id;
        } else {
            if (isset($_SESSION['ids'])) {
                $managers_id = $_SESSION['ids'];
            }
        }

        if ($request->get('start_month')){
            $start_month = $request->get('start_month');
            $_SESSION['start_month'] = $start_month;
        } else {
            if (isset($_SESSION['start_month'])) {
                $start_month = $_SESSION['start_month'];
            }
        }

        if ($request->get('last_month')){
            $last_month = $request->get('last_month');
            $_SESSION['last_month'] = $last_month;
        } else {
            if (isset($_SESSION['last_month'])) {
                $last_month = $_SESSION['last_month'];
            }
        }

        if ($request->get('comission')!==null and $request->get('id')!==null){
            $comission = (float) $request->get('comission');
            $id = (int) $request->get('id');
            $manager = $managerRepository->find($id);
            $manager->setPercentageComission($comission);
            $this->em->persist($manager);
            $this->em->flush();
        }

        if ($request->get('fine')!==null or $request->get('premium')!==null){
            $request->get('fine')!==null ? $amount = - (float) $request->get('amount') : $amount = $request->get('amount');
            $id = (int) $request->get('id');
            $date = \DateTime::createFromFormat("Y-m-d", $request->get('date'));
            $fb = new FineAndBonus();
            $fb->setManager($managerRepository->find($id));
            $fb->setAmount($amount);
            $fb->setDateOfEnd($date);
            $fb->setDateOfStart($date);
            $this->em->persist($fb);
            $this->em->flush();
        }

        $start_month_str = $this->convert_date($start_month);
        $last_month_str = $this->convert_date($last_month);
        $first_date = \DateTime::createFromFormat("Y-m", $start_month_str)->format("Y-m-01");
        $last_date = \DateTime::createFromFormat("Y-m", $last_month_str)->format("Y-m-t");
        $first_real_date = \DateTime::createFromFormat("Y-m-d", $first_date);
        $last_real_date = \DateTime::createFromFormat("Y-m-d", $last_date);

        foreach ($managers_id as $id){
            $all_months = array();
            $first_real_date_temp = \DateTime::createFromFormat("Y-m", $start_month_str);
            while ($first_real_date_temp<$last_real_date){
                $this_month = array();
                $month_acts = $actionRepository->getByManagerAndDate($id, $first_real_date_temp);
                $this_month['month'] = $first_real_date_temp->format("Y-m");
                $this_month['actions'] = array();
                $this_month['fb'] = array();
                $fb = $fineAndBonusRepository->findByManagerAndMonth($id, $first_real_date_temp);
                foreach ($fb as $item){
                    $item->getAmount()>0 ? $s = "Премия " : $s = "Штраф ";
                    $this_month['fb'][] = [
                        'amount' => $s . $item->getAmount(),
                        'date' => $item->getDateOfEnd()->format("Y-m-d")
                    ];
                }
                foreach ($month_acts as $item){
                    $this_month['actions'][] = [
                        'action' => $item->getActionType()->getActionTypeName() . " " . $item->getProduct()->getCar()->getBrand() . " " . $item->getProduct()->getCar()->getModel(),
                        'date' => $item->getDate()->format("Y-m-d")
                    ];
                }
                if(!$payRepository->findOneByManagerAndMonth($id, $first_real_date_temp)){
                    $payController->refreshOrUpdatePay($managerRepository->find($id), $payRepository, $actionRepository, $first_real_date_temp->format("Y-m"), new \DateTime(), $fineAndBonusRepository, $managerTimesheetRepository);
                    $this->em->flush();
                }
                $this_month['amount'] = $payRepository->findOneByManagerAndMonth($id, $first_real_date_temp)->getAmount();
                $this_month['days'] = $payRepository->findOneByManagerAndMonth($id, $first_real_date_temp)->getWorkingDays();

                $all_months[]=$this_month;
                $first_real_date_temp->modify("+1 month");
            }




            $managers[$id] = ['id'=>$id,
                                'monthes'=>$all_months,
                                'fio'=>$managerRepository->find($id)->getManagerName(),
                              'comission'=>$managerRepository->find($id)->getPercentageComission()];
        }

        return $this->render('manager/index.html.twig', [
            'controller_name' => 'ManagerController',
            'start_month_str' => $start_month_str,
            'last_month_str' => $last_month_str,
            'managers' => $managers
        ]);
    }

    private function convert_date(String $date): String
    {
        if($date=="") {
            return date('Y')."-".date('m');
        } else {
            return $date;
        }

    }
}
