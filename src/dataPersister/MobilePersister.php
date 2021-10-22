<?php

namespace App\dataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Mobile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//use Symfony\Component\HttpFoundation\Response;


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
    public function supports($data): Bool
    {
        return $data instanceof Mobile;
    }

    /**
     * @inheritDoc
     */
    public function persist($data)
    {
        $state = $data->getState();
        $storage = $data->getStockage();
        $color = $data->getColor();
        $brandName = $data->getBrand()->getBrandName();
        $model = $data->getModel();
        $description = $brandName.' '.$model.' '.$storage.'Go '.$color;
        $title = $description;

        switch ($state) {
            case 'A+':
                $data->setTitle((string)$description.' comme neuf');
                $data->setDescription($description. ' comme neuf vendu avec ses accessoires d\'origine');
                break;
            case 'A':
                $data->setTitle((string)$description.' en excellent bon état');
                $data->setDescription($description. ' en excellent état vendu avec ses accessoires d\'origine');
                break;
            case 'B+':
                $data->setTitle((string)$description.' en très bon état');
                $data->setDescription($description. ' en très bon état vendu avec ses accessoires d\'origine');
                break;
            case 'B':
                $data->setTitle((string)$description.' en bon état');
                $data->setDescription($description. ' en bon état vendu avec ses accessoires d\'origine');
                break;
            case 'C+':
                $data->setTitle((string)$description.' état moyen');
                $data->setDescription($description. ' en état moyen avec des rayures visibles à 20cm, vendu avec ses accessoires d\'origine');
                break;
            case 'C':
                $data->setTitle((string)$description.' état passable');
                $data->setDescription($description. ' en mauvais état estétique, mais en parfait état de fonctionnement, vendu avec ses accessoires d\'origine');
                break;
        }

        $data->setTitle((string)$title);
        $data->setCreatedAt(new \DateTimeImmutable());
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