<?php

namespace App\Controller;

use App\Entity\Mobile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MobileRepository;
use Doctrine\ORM\EntityManagerInterface;


class MobileController extends AbstractController
{

    /**
     * @var MobileRepository
     */
    private $mobile_manager;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager, MobileRepository $mobile_manager)
    {
        $this->manager = $manager;
        $this->mobile_manager = $mobile_manager;
    }

//    /**
//     * @Route("/mobile", name="mobile")
//     */
    public function index(): Response
    {
        return $this->render('mobile/index.html.twig', [
            'controller_name' => 'MobileController',
        ]);
    }

//    /**
//     * @param Mobile $data
//     * @return Mobile
//     * @Route("/apip/mobiles", name="mobiles")
//     */
    /*public function get_list(Mobile $data): Mobile
    {
       $this->mobile_manager->findAll();
        foreach ($mobiles as $mobile) {
            return $mobile;
        }
        return $data;
    }*

    /**
     * @param $id
     * @return Response
     * @Route("/mobile/{id}", name="singleMobile")
     */
    /*public function get_by_id($id): Response
    {
        $mobile = $this->mobile_manager->findOneBy($id);
    }*/

//    /**
//     * @param array $params
//     * @return Response
//     * @Route("/mobiles/{params}", name="mobile_params")
//     */
    /*public function get_mobiles_params(array $params): Response
    {
        if($params) {
            foreach ($params as $key => $value){
                $p = [$key => $value];
                $mobiles = $this->mobile_manager->findBy($p, ['brand_id' => 'DESC']);
            }
        } else {
            $mobiles = $this->mobile_manager->findBy(['brand_id' => 'DESC']);
        }

    }*/
}
