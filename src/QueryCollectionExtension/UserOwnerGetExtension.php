<?php

namespace App\QueryCollectionExtension;


use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Client;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserOwnerGetExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $tokenStorage;
    private $currentUser;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        $this->currentUser = $this->tokenStorage->getToken()->getUser();
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $this->filterByCurrentUser($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        $this->filterByCurrentUser($queryBuilder, $resourceClass);
    }

    private function filterByCurrentUser($queryBuilder, $resourceClass){
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $userId = $this->currentUser->getId();

        if(User::class === $resourceClass){
            preg_match('/[0-9]/', $_SERVER['REQUEST_URI'], $matches);
            if($userId == $matches[0] && $this->currentUser->getRoles() == ['ROLE_ADMIN']) {
                $queryBuilder->andWhere(sprintf('%s.id = :current_user', $rootAlias));
                $queryBuilder->setParameter('current_user', $userId);

            } elseif($this->currentUser->getRoles() == ['ROLE_SUPERADMIN']) {
                return;
            }
            else {
                dd('Attention vous n\'avez pas accès à cette ressource');
            }
        } elseif (Client::class === $resourceClass) {
            if($this->currentUser->getRoles() == ['ROLE_ADMIN']) {
//                $queryBuilder->innerJoin(sprintf('%s.user', $rootAlias), 'u' );
//
                $queryBuilder->andWhere(sprintf('%s.user = :current_user', $rootAlias));
                $queryBuilder->setParameter('current_user', $userId);

            } elseif($this->currentUser->getRoles() == ['ROLE_SUPERADMIN']) {
                return;
            }
            else {
                dd('Attention vous n\'avez pas accès à cette ressource');
            }
        }

    }
}