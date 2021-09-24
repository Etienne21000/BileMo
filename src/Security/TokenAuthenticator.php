<?php


namespace App\Security;

use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use App\Service\TokenService;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $json;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization')
            && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    /**
     * @param Request $request
     * @return string|null
     */
    public function getCredentials(Request $request)
    {

        $authorizationHeader = $request->headers->get('Authorization');

        return substr($authorizationHeader, 7);

    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if ($credentials === null) {
            throw new UnsupportedHeaderFound('Le header ne fonctionne pas');
        }

        $token = new TokenService();
        $jwt = $token->decryptToken($credentials);
        $user_email = $jwt->get('jti');
        $expire_token = $jwt->get('exp');
        $calc = $token->validateTimeToken($expire_token);

        if(!$calc) {
            return $this->json->json([
                'error' => 'Attention, la periode de validité du token a expiré',
            ], 400);
        } else {
            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $user_email]);
        }
        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Attention, cet utilisateur est inconnue');
        }
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
            'message_erreur' => 'Attention l\'identifiant ou le mot de passe est incorrect'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $data = [
            'message' => 'Attention vous devez être connecté'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}