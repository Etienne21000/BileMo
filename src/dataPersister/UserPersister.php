<?php

namespace App\dataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPersister implements DataPersisterInterface
{
    private $em;
    private $passhash;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passhash)
    {
        $this->em = $em;
        $this->passhash = $passhash;
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