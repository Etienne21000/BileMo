<?php

namespace App\Service;

use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
//use Lcobucci\JWT\Validation\ConstraintViolation;
//use Lcobucci\JWT\UnencryptedToken;
//use Lcobucci\JWT\ValidationData;

class TokenService
{
    public function createTokenFromUserAuthentication($username) {

        $now = new DateTimeImmutable();
        $actual_time = $now->modify('+2 hours');

        $config = Configuration::forSymmetricSigner(new Sha256(), $key = InMemory::base64Encoded('BileMoApiRestPrivateKey'));
        $jwt = $config->builder()
            ->issuedBy('https://'.$_SERVER['HTTP_HOST'].'/api')
            ->withHeader('iss', 'https://'.$_SERVER['HTTP_HOST'].'/api')
            ->permittedFor('https://'.$_SERVER['HTTP_HOST'].'/api')
            ->identifiedBy($username)
            ->issuedAt($actual_time)
            ->canOnlyBeUsedAfter($actual_time)
            ->expiresAt($actual_time->modify('+1 hour'))
            ->getToken($config->signer(), $config->signingKey());

        return $jwt->toString();
    }

    public function decryptToken($credentials) {
        $token = str_replace('Bearer ', '', $credentials);
        $parser = new Parser();
        $jwt = $parser->parse($token);

        return $jwt->claims();
    }

}