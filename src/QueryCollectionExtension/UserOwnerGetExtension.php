<?php

namespace App\QueryCollectionExtension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Client;
use App\Entity\User;
use App\Entity\Mobile;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;


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
        //dd($this->currentUser);
        $this->filterByCurrentUser($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
            $this->filterByCurrentUser($queryBuilder, $resourceClass);
    }

    private function filterByCurrentUser($queryBuilder, $resourceClass){

//        dd($this->tokenStorage->getToken());
        if($this->tokenStorage->getToken() !== null) {
            $this->currentUser = $this->tokenStorage->getToken()->getUser();
            $userId = $this->currentUser->getId();
        }
        $rootAlias = $queryBuilder->getRootAliases()[0];
        preg_match('/[0-9]/', $_SERVER['REQUEST_URI'], $matches);

        switch ($resourceClass){
            case User::class:
                if($matches){
                    if($userId == $matches[0] && $this->currentUser->getRoles() == ['ROLE_ADMIN']) {
                        $queryBuilder->andWhere(sprintf('%s.id = :current_user', $rootAlias));
                        $queryBuilder->setParameter('current_user', $userId);

                    } elseif($this->currentUser->getRoles() == ['ROLE_SUPERADMIN']) {
                        return;
                    }
                    else {
                        throw new BadRequestException('Attention vous avez uniquement accès aux données user vous concernant ');
                    }
                }

                break;
            case Client::class:
                if($matches){
                    if($userId == $matches[0] && $this->currentUser->getRoles() == ['ROLE_ADMIN']) {
                        $queryBuilder->andWhere(sprintf('%s.user = :current_user', $rootAlias));
                        $queryBuilder->setParameter('current_user', $userId);

                    } elseif($this->currentUser->getRoles() == ['ROLE_SUPERADMIN']) {
                        return;
                    }
                    else {
                        throw new BadRequestException('Attention vous n\'avez pas accès à cette ressource !!!');
                    }
                }
                break;
            /*case Mobile::class:
//                $mobile = new Mobile();
//                $id =
                if($matches){
//                    throw new BadRequestException('Attention ce mobile n\'héxiste pas');
//                    $id = $mobile->getId();
                    try{
                        $queryBuilder->andWhere(sprintf('%s.id = :id', $rootAlias));
                        $queryBuilder->setParameter('id', $matches[0]);
                    } catch (\Exception $e) {
                        throw new BadRequestException('Attention ce mobile n\'héxiste pas');
                    }
                }
                break;*/
            default:
                return;
                break;
        }
    }
}