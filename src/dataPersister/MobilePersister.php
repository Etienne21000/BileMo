<?php

namespace App\dataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Mobile;
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
    public function supports($data): Bool
    {
        return $data instanceof Mobile;
    }

    private function dataPersisterPresetSwitch($data, $state, $description){
        $paramDescription = '';
        switch ($state) {
            case 'A+':
                $paramDescription = ' comme neuf vendu avec ses accessoires d\'origine';
                break;
            case 'A':
                $paramDescription = ' en excellent état vendu avec ses accessoires d\'origine';
                break;
            case 'B+':
                $paramDescription = ' en très bon état vendu avec ses accessoires d\'origine';
                break;
            case 'B':
                $paramDescription = ' en bon état vendu avec ses accessoires d\'origine';
                break;
            case 'C+':
                $paramDescription = ' en état moyen avec des rayures visibles à 20cm, vendu avec ses accessoires d\'origine';
                break;
            case 'C':
                $paramDescription = ' en mauvais état estétique, mais en parfait état de fonctionnement, vendu avec ses accessoires d\'origine';
                break;
        }
        return $paramDescription;
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
        $paramDescription = $this->dataPersisterPresetSwitch($data, $state, $description);
        if(!$data->getDescription()){
            $data->setDescription($description.$paramDescription);
        }
        $data->setTitle((string)$description.' '.$state);
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