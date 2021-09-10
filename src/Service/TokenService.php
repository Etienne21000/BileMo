<?php

namespace App\Service;

use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\ValidationData;

class TokenService
{
    private $user;
    private $user_id;

//    public function __construct($user, $user_id)
//    {
//        $this->user = $user;
//        $this->user_id = $user_id;
//    }

    public function createTokenFromUserAuthentication($username, $user_id) {
//        $username = $this->user;
//        $user_id = $this->user_id;
        //json_encode($user);
//        $container = new Sha256();
//        $signer = new Sha256();
//        $username = 'bilemo@mail.com';
//        $user_id = 1;

        $now = new DateTimeImmutable();
        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::base64Encoded('testing'));
        $jwt = $config->builder()
            ->issuedBy('https://localhost:8000/api/users')
            ->withHeader('iss', 'https://localhost:8000/api/users')
            ->permittedFor('https://localhost:8000/api/users')
            ->identifiedBy($user_id)
            ->relatedTo($username)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now->modify('+1 minute'))
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('uid', $user_id)
            ->getToken($config->signer(), $config->signingKey());

        return $jwt->toString();
    }

//    public function find(){
////        $auth = $headers['Authorization'];
////        if (preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
//        $jwt = $this->createTokenFromUserAuthentication();
//            $token = (new Parser())->parse((string) $jwt);
//            $data = new ValidationData();
//            $data->setIssuer($token);
//            $data->setAudience($token);
//            $data->setId('4f1g23a12aa');
//            return $token->validate($data);
////        }else{
////            dd('nope');
////        }
//    }

}