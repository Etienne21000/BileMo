<?php

namespace App\Service;

use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\ConstraintViolation;
//use Lcobucci\JWT\UnencryptedToken;
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

        $now = new DateTimeImmutable();
        $config = Configuration::forSymmetricSigner(new Sha256(), $key = InMemory::base64Encoded('BileMoApiRestPrivateKey'));
        $jwt = $config->builder()
            ->issuedBy('https://'.$_SERVER['HTTP_HOST'].'/api')
            ->withHeader('iss', 'https://'.$_SERVER['HTTP_HOST'].'/api')
            ->permittedFor('https://'.$_SERVER['HTTP_HOST'].'/api')
            ->identifiedBy($username)
            ->relatedTo($user_id)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('uid', $user_id)
            ->getToken($config->signer(), $config->signingKey());

        return $jwt->toString();
    }
    
}