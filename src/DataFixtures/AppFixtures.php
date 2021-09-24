<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Mobile;
use App\Entity\Brand;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        $user_1 = (new User())
            ->setUsername('BileMo')
            ->setCreationDate(new \DateTime())
            ->setEmail('bilemo@mail.com')
            ->setCreationDate(new \DateTimeImmutable())
            ->setRoles(['ROLE_SUPERADMIN']);
        $manager->persist($user_1);
        $user_1->setPassword($this->passwordHasher->hashPassword(
            $user_1,
            'Equinox75!'
        ));

        $user_2 = (new User())
            ->setUsername('MobileDetect')
            ->setCreationDate(new \DateTime())
            ->setEmail('mobiledetect@mail.com')
            ->setCreationDate(new \DateTimeImmutable())
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($user_2);
        $user_2->setPassword($this->passwordHasher->hashPassword(
            $user_2,
            'Equinox75!'
        ));

        for ($i = 0; $i < 60; $i++) {

            $client_1 = (new Client())
                ->setName($faker->lastName);
            $manager->persist($client_1);
            $client_1
                ->setEmail($client_1->getName().'@mail.com')
                ->setCreationDate(new \DateTimeImmutable())
                ->setUser($user_2);
            $manager->persist($client_1);

        }

        for ($i = 0; $i < 60; $i++) {

            $address_user = (new Address())
                ->setAddress($faker->address)
                ->setCp('75002')
                ->setCity($faker->city)
                ->setType(1);
            $manager->persist($address_user);
            $address = [$address_user];

            foreach ($address as $add) {
                $user_1->addAddress($add);
                $user_2->addAddress($add);
                $client_1->addAddress($add);
                $manager->persist($user_1);
                $manager->persist($user_2);
                $manager->persist($client_1);
            }
        }

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
            $state = $this->mobile_state();

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
                ->setCreatedAt(new \DateTimeImmutable())
                ->setState($state);
            $state_descp = $this->state_description($mobile1->getState());

            $mobile1->setDescription($brand_id.' '.$model . ' reconditionné '.$state_descp.'.');
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

    public function mobile_state(){
        $state = ['A+', 'A', 'B+', 'B', 'C+', 'C'];
        shuffle($state);
        foreach ($state as $value){
            $resp = $value;
        }
        return $resp;
    }

    public function state_description($state){
        switch ($state){
            case 'A+':
                $resp = 'état comme neuf';
                break;
            case 'A':
                $resp = 'très bon état';
                break;
            case 'B+':
                $resp = 'bon état';
                break;
            case 'B':
                $resp = 'avec micro rayures non visibles à 10cm';
                break;
            case 'C+':
                $resp = 'en état moyen avec des rayures visibles à 20cm ';
                break;
            case 'C':
                $resp = 'en mauvais état estétique, mais en parfait état de fonctionnement';
                break;
        }
        return $resp;
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

