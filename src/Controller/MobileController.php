<?php

namespace App\Controller;

use App\Entity\Mobile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
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
}
