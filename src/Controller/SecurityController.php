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
//        $token = new TokenService();
//        $token->createTokenFromUserAuthentication($user->getUserIdentifier());
//        return $this->json([
//           'username' => $user->getUserIdentifier(),
//            'role' => $user->getRoles(),
////            'token' => $token,
//
////            'token' => new JsonResponse(['token' => $token]),
//        ]);
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Attention une erreur s\'est produite'
            ], 400);
        }
        return $this->json([
           'username' => $user->getUserIdentifier(),
            'role' => $user->getRoles(),
            'user_id' => $user->getId(),
//            'token' => $token,

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
