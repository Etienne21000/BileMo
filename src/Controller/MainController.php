<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TokenService;

class MainController extends AbstractController
{
    private $em;

    /**
     * @Route("/", name="home")
     */
    public function index(): Response {

        $colors = [
            'noir',
            'blanc',
            'rouge',
            'jaune',
            'gold',
            'argent',
            'violet'
        ];

        shuffle($colors);

        foreach ($colors as $color){
            $resp = $color;
        }

        $token = new TokenService();
//        $headers = ['Authorization: Bearer'];
        $resp_token = $token->createTokenFromUserAuthentication($username = 'bilemo@mail.com');
        return $this->render('base.html.twig', [
            'color' => $resp,
            'token' => $resp_token,
        ]);
    }

}