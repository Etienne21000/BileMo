<?php

namespace App\dataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\DataPersisterHelper;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ClientPersister implements DataPersisterInterface
{

    private $em;
    private $helper;
    private $tokenStorage;
    private $user;

    /**
     * ClientPersister constructor.
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->helper = new DataPersisterHelper();
        $this->user = $this->tokenStorage->getToken()->getUser();
    }

    /**
     * @param $data
     * @return bool
     */
    public function supports($data): bool
    {
        return $data instanceof Client;
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function persist($data)
    {
        if($data->getEmail()){
            $email = $data->getEmail();
            $regex = '/[^@]*/';
            $data->setName($this->helper->splitAndReplaceUsername($email, $regex, 0));
        }
        $data->setCreationDate(new \DateTimeImmutable());
        $data->setUser($this->user);
        $this->em->persist($data);
        $this->em->flush();
    }

    /**
     * @param $data
     */
    public function remove($data)
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}