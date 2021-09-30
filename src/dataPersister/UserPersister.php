<?php

namespace App\dataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use App\Service\DataPersisterHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPersister implements DataPersisterInterface
{
    private $em;
    private $passhash;
    private $helper;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passhash)
    {
        $this->em = $em;
        $this->passhash = $passhash;
        $this->helper = new DataPersisterHelper();
    }

    /**
     * @inheritDoc
     */
    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @inheritDoc
     */
    public function persist($data)
    {
        if($data->getPassword()) {
            $data->setPassword(
                $this->passhash->hashPassword($data, $data->getPassword())
            );
        }
        if($data->getEmail()){
            $email = $data->getEmail();
            $regex = '/[^@]*/';
            $data->setUsername($this->helper->splitAndReplaceUsername($email, $regex, 0));
        }
        $data->setCreationDate(new \DateTimeImmutable());

        $this->em->persist($data);
        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function remove($data)
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}