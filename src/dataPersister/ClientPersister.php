<?php

namespace App\dataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

class ClientPersister implements DataPersisterInterface
{

    private $em;

    /**
     * ClientPersister constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    public function supports($data): bool
    {
        return $data instanceof Client;
    }

    /**
     * @inheritDoc
     */
    public function persist($data)
    {
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