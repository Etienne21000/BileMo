<?php

namespace App\Controller;

use App\Service\TokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     * @return Response
     */
    public function apiLogin()
    {
        $user = $this->getUser();
        $token = new TokenService();
        $jwt = $token->createTokenFromUserAuthentication($user->getUserIdentifier());

            if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
                return $this->json([
                    'error' => 'Attention, vous devez être conneté'
                ], 400);
            }

            if (!$user) {
                return $this->json([
                    'error' => 'Attention aucun utilisateur',
                ], 400);
            }

            return $this->json([
                'token' => 'Bearer '.$jwt,
            ]);
    }

    /**
     * @Route("/api/logout", name="api_logout", methods={"POST"})
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
