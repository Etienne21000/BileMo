<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Mobile;
use App\Entity\Brand;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $apple = (new Brand())->setBrandName('Apple');
        $manager->persist($apple);
        $samsung = (new Brand())->setBrandName('Samsung');
        $manager->persist($samsung);
        $xiaomi = (new Brand())->setBrandName('Xiaomi');
        $manager->persist($xiaomi);
        $huawei = (new Brand())->setBrandName('Huawei');
        $manager->persist($huawei);
        $oneplus = (new Brand())->setBrandName('OnePlus');
        $manager->persist($oneplus);
        $lenovo = (new Brand())->setBrandName('Lenovo');
        $manager->persist($lenovo);

        $brand_array = [$apple, $samsung, $xiaomi, $huawei, $oneplus, $lenovo];

        for ($i = 0; $i < 60; $i++) {
            $brand = $this->mobile_brand($brand_array);
            $color = $this->colors();
            $storage = $this->storage();
            $IMEI = $this->IMEI();

            $mobile1 = (new Mobile())
                ->setBrand($brand);
            $manager->persist($mobile1);

            $brand_id = $mobile1->getBrand()->getBrandName();

            $model = $this->mobile_model($brand_id);
            $mobile1->setModel($model);
            $mobile1->setTitle($model)
                ->setColor($color)
                ->setIMEI($IMEI)
                ->setStockage($storage)
                ->setDescription($brand_id.' '.$model . ' reconditionné état comme neuf.');
            $manager->persist($mobile1);
            $price = $this->set_price($mobile1->getStockage());
            $mobile1->setPrice($price);
            $manager->persist($mobile1);
        }
        $manager->flush();
    }

    public function colors()
    {
        $colors = ['noir', 'blanc', 'rouge', 'jaune', 'gold', 'argent', 'violet'];

        shuffle($colors);

        foreach ($colors as $color) {
            $resp = $color;
        }
        return $resp;
    }

    public function storage(){
        $storages = [32, 64, 128, 256, 512];

        shuffle($storages);

        foreach ($storages as $storage){
            $resp = $storage;
        }
        return $resp;
    }

    public function set_price($storage){
        switch ($storage) {
            case 32:
                $resp = 409;
                break;
            case 64:
                $resp = 504;
                break;
            case 128:
                $resp = 609;
                break;
            case 256:
                $resp = 809;
                break;
            case 512:
                $resp = 909;
                break;
        }
        return $resp;
    }

    public function IMEI(){
        return rand(11111111, 99999999);
    }

    public function mobile_model($brand){

        if($brand === 'Apple'){
            $models = ['iPhone 12 Pro', 'iPhone 12', 'iPhone 11', 'iPhone SE'];
            shuffle($models);
            return $this->models_get($models);

        } elseif ($brand === 'Samsung'){
            $models = ['Galaxy s20', 'Galaxy s21', 'Galaxy s10', 'Galaxy s8'];
            shuffle($models);
            return $this->models_get($models);

        } elseif ($brand === 'Xiaomi') {
            $models = ['Mi 11 Ultra', 'Mi 11i', 'Mi 11', 'Mi 11 Lite'];
            shuffle($models);
            return $this->models_get($models);

        } elseif ($brand === 'Huawei') {
            $models = ['Mate 40', 'P40', 'P40 Pro', 'P40 Pro+'];
            shuffle($models);
            return $this->models_get($models);

        } elseif ($brand === 'OnePlus') {
            $models = ['Nord 2', '9', '9 Pro', 'Nord CE 5g'];
            shuffle($models);
            return $this->models_get($models);

        } elseif ($brand === 'Lenovo') {
            $models = ['Legion', 'MotoG10', 'MotoG3', 'MotoG5'];
            shuffle($models);
            return $this->models_get($models);

        }
    }

    public function models_get(array $models) {
        foreach ($models as $model){
            $resp = $model;
        }
        return $resp;
    }

    public function mobile_brand($brand_array) {
        shuffle($brand_array);

        foreach ($brand_array as $b){
            $resp = $b;
        }
        return $resp;
    }
}

