<?php

namespace App\Service;

use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class TokenService
{
    public function createTokenFromUserAuthentication($username) {

        $now = new DateTimeImmutable();
        $now->format('Y-m-d H:i:s');
        $actual_time = $now->modify('+2 hours');

        $config = Configuration::forSymmetricSigner(new Sha256(), $key = InMemory::base64Encoded('BileMoApiRestPrivateKey'));
        $jwt = $config->builder()
            ->issuedBy('https://'.$_SERVER['HTTP_HOST'].'/api')
            ->withHeader('iss', 'https://'.$_SERVER['HTTP_HOST'].'/api')
            ->permittedFor('https://'.$_SERVER['HTTP_HOST'].'/api')
            ->identifiedBy($username)
            ->issuedAt($actual_time)
            ->canOnlyBeUsedAfter($actual_time)
            ->expiresAt($actual_time->modify('+5 days'))
            ->getToken($config->signer(), $config->signingKey());

        return $jwt->toString();
    }

    public function decryptToken($credentials) {
        $token = str_replace('Bearer ', '', $credentials);
        $parser = new Parser();
        $jwt = $parser->parse($token);

        return $jwt->claims();
    }

    public function validateTimeToken($exp) {
        $expire_token = $exp;
        $now = new DateTimeImmutable();
        $resp_time = $now->modify('+2 hours');
        $resp_time->format('Y-m-d H:i:s');
        $calc = $resp_time > $expire_token;

        if($calc) {
            $validity = false;
        }
        else {
            $validity = true;
        }
        return $validity;
    }

}