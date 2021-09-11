<?php


namespace App\Security;

use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Symfony\Config\LexikJwtAuthentication;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Lcobucci\JWT\Signer\Key\InMemory;
//use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Validator\Constraints\Time;
use App\Service\TokenService;
use function Doctrine\Common\Cache\Psr6\expiresAt;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $token;

//    public const LOGIN_ROUTE = 'api_login';

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
//        $this->token = $token;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization: Bearer');
//            && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    /**
     * @param Request $request
     * @return string|null
     */
    public function getCredentials(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');
        // skip beyond "Bearer "
        return substr($authorizationHeader, 7);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {

//        $jwt = $credentials;
        if ($credentials === null) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            throw new UnsupportedHeaderFound('Le header ne fonctionne pas');
            //return null;
        }

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Attention, cet utilisateur est inconnue');
        } else {

            $token = new TokenService();
            $token->createTokenFromUserAuthentication($user->getUserIdentifier(), $user->getId());

        }
//        return $token;
        return new JsonResponse(['token' => $token]);
//        return $userProvider->loadUserByIdentifier($credentials['username']);
        //dump($credentials);die;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        // Check credentials - e.g. make sure the password is valid.
        // In case of an API token, no credential check is needed.
        //dd($credentials);
        // Return `true` to cause authentication success
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
            'message_erreur' => 'Attention l\'identifiant ou le mot de passe est incorrect'
            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
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
            'message' => 'Attention vous devez etre connecte'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(Request $request): PassportInterface
    {
        // TODO: Implement authenticate() method.
    }
}