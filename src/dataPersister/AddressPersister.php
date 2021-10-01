<?php

namespace App\dataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;

class AddressPersister implements DataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
//    private $Address;

    /**
     * AddressPersister constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
//        $this->Address = new Address();
    }

    /**
     * @inheritDoc
     */
    public function supports($data): bool
    {
        return $data instanceof Address;
    }

    /**
     * @inheritDoc
     */
    public function persist($data)
    {
//        $data->setCreationDate(new \DateTime("Y-m-d"));
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