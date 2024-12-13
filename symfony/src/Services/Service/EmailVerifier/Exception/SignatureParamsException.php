<?php

namespace App\Services\Service\EmailVerifier\Exception;

final class SignatureParamsException extends \Exception
{
    public function __construct(string $param)
    {
        parent::__construct('Signature params are not valid. Missing: '.$param);
    }
}
