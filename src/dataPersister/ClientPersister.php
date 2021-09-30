<?php

namespace App\dataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\DataPersisterHelper;

class ClientPersister implements DataPersisterInterface
{

    private $em;
    private $helper;

    /**
     * ClientPersister constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->helper = new DataPersisterHelper();
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
        if($data->getEmail()){
            $email = $data->getEmail();
            $regex = '/[^@]*/';
            $data->setName($this->helper->splitAndReplaceUsername($email, $regex, 0));
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