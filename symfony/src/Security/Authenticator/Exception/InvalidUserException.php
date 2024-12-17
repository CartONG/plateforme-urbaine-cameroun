<?php

namespace App\Security\Authenticator\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class InvalidUserException extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey(): string
    {
        return 'User is not validated';
    }
}
