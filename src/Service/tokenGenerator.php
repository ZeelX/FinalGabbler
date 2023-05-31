<?php

namespace App\Service;

use Exception;

class tokenGenerator
{

    /**
     * @throws Exception
     */
    function generateToken($length = 32): string
    {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }
}