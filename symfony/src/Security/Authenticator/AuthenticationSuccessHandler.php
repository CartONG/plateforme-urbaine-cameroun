<?php

namespace App\Security\Authenticator;

use App\Entity\User\User;
use App\Security\Authenticator\Exception\InvalidUserException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler as LexikAuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private readonly LexikAuthenticationSuccessHandler $authenticationSuccessHandlerDecorated,
    ) {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        /** @var User $user */
        $user = $token->getUser();
        if (!$user->getIsValidated()) {
            throw new InvalidUserException('User is not validated');
        }

        return $this->authenticationSuccessHandlerDecorated->onAuthenticationSuccess($request, $token);
    }
}
