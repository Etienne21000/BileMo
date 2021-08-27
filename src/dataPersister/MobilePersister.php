<?php

namespace App\dataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Mobile;
use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;


class MobilePersister implements DataPersisterInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * MobilePersister constructor.
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
        return $data instanceof Mobile;
    }

    /**
     * @inheritDoc
     */
    public function persist($data)
    {
//        $data->setCreationDate(new \DateTime());
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