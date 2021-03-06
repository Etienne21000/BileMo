<?php

namespace App\QueryCollectionExtension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Client;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserOwnerGetExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $tokenStorage;
    private $currentUser;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $this->filterByCurrentUser($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        $this->filterByCurrentUser($queryBuilder, $resourceClass);
    }

    /**
     * @return mixed
     */
    private function getTokenAuthentication() {
        if ($this->tokenStorage->getToken() !== null) {
            $this->currentUser = $this->tokenStorage->getToken()->getUser();
            return $userId = $this->currentUser->getId();
        } else {
            throw new BadRequestException('Attention, vous devez vous connecter');
        }
    }

    private function checkUserOwnerDatas($matches, $queryBuilder, $rootAlias) {
        $userId = $this->getTokenAuthentication();
        if($matches){
            if($userId == $matches[0] && $this->currentUser->getRoles() == ['ROLE_ADMIN']) {
                $queryBuilder->andWhere(sprintf('%s.id = :current_user', $rootAlias));
                $queryBuilder->setParameter('current_user', $userId);
            } elseif($this->currentUser->getRoles() == ['ROLE_SUPERADMIN']) {
                return;
            } else { throw new BadRequestException('Attention vous avez uniquement acc??s aux donn??es user vous concernant ');}
        } else {
            if($this->currentUser->getRoles() == ['ROLE_SUPERADMIN']){
                return;
            } else { throw new BadRequestException('Attention, vous n\'avez pas acc??s ?? ces ressources');}
        }
    }

    Private function checkUserOwnerClientsDatas($matches, $queryBuilder, $rootAlias, $method) {
        $userId = $this->getTokenAuthentication();
        if($matches){
            $queryBuilder->andWhere(sprintf('%s.id = :client', $rootAlias));
            $queryBuilder->setParameter('client', $matches);
            $client = $queryBuilder->getQuery()->getResult();
            if ($client) {
                if ($userId && $this->currentUser->getRoles() == ['ROLE_ADMIN'] && ($method === 'GET' || $method === 'DELETE' || $method === 'PUT')) {
                    $queryBuilder->andWhere(sprintf('%s.user = :current_user', $rootAlias));
                    $queryBuilder->setParameter('current_user', $userId);
                    $resp = $queryBuilder->getQuery()->getResult();
                    if (!$resp) {
                        throw new BadRequestException('Attention, vous n\'avez acc??s qu\'?? votre liste de clients ');
                    } else { return;}
                } elseif ($this->currentUser->getRoles() == ['ROLE_SUPERADMIN']) {
                    return;
                } else { throw new BadRequestException('Attention vous n\'avez pas acc??s ?? cette ressource !!!');}
            } elseif (!$client) { throw new BadRequestException('Attention, ce client n\'existe pas');}
        } else {
            if($userId && $this->currentUser->getRoles() == ['ROLE_ADMIN']) {
                $queryBuilder->andWhere(sprintf('%s.user = :current_user', $rootAlias));
                $queryBuilder->setParameter('current_user', $userId);
            } elseif($this->currentUser->getRoles() == ['ROLE_SUPERADMIN']) {
                return;
            } else {throw new BadRequestException('Attention vous n\'avez pas acc??s ?? cette ressource !!!');}
        }
    }

    /**
     * @param $queryBuilder
     * @param $resourceClass
     */
    private function filterByCurrentUser($queryBuilder, $resourceClass){
        $rootAlias = $queryBuilder->getRootAliases()[0];
        preg_match('/[0-9]+/', $_SERVER['REQUEST_URI'], $matches);
        $req = Request::createFromGlobals();
        $method = $req->getMethod();
        switch ($resourceClass){
            case User::class:
                $this->checkUserOwnerDatas($matches, $queryBuilder, $rootAlias);
                break;
            case Client::class:
                $this->checkUserOwnerClientsDatas($matches, $queryBuilder, $rootAlias, $method);
                break;
            default:
                return;
                break;
        }
    }
}