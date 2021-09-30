<?php

namespace App\Service;


class DataPersisterHelper
{

    public function splitAndReplaceUsername($email, $regex, $number) {
        preg_match($regex, $email, $matches);
        $regex = '/[\W]/i';
        return preg_replace($regex, ' ', $matches[$number]);
    }
}