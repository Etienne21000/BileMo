<?php

namespace App\dataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Mobile;
use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class MobilePersister implements DataPersisterInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
//    private $decorated;

    /**
     * MobilePersister constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
//        $this->decorated = $decorated;
    }

    /**
     * @inheritDoc
     */
    public function supports($data): Bool
    {
        return $data instanceof Mobile;
//        return $this->decorated->supports($data, $context);
    }

    /**
     * @inheritDoc
     */
    public function persist($data)
    {/*
        if($context['post']) {

        }

        if($context['put']) {

            $data->setModifiedAt(new \DateTimeImmutable());
        }*/

        /*if(Request::METHOD_POST ) {
            $data->setCreatedAt(new \DateTimeImmutable());
        }
        if(Request::METHOD_PUT || Request::METHOD_PATCH) {
            $data->setModifiedAt(new \DateTimeImmutable());
        }*/
        $data->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($data);
        $this->em->flush();
//        return $this->decorated->persist($data, $context);

    }

    /**
     * @inheritDoc
     */
    public function remove($data)
    {
        $this->em->remove($data);
        $this->em->flush();
//        return $this->decorated->remove($data, $context);
    }
}