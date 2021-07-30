<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
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

        return $this->render('base.html.twig', [
//            'php' => $php,
            'color' => $resp,
        ]);
    }

}