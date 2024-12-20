<?php

namespace App\Services\Service\EmailVerifier;

use App\Entity\User\User;
use App\Services\Service\EmailVerifier\Dto\EmailVerifierVerifyDto;
use App\Services\Service\EmailVerifier\Exception\SignatureParamsException;
use Symfony\Component\HttpFoundation\UriSigner;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Security\Core\Signature\SignatureHasher;

class EmailVerifierSignature
{
    private const string QUERY_PARAM_TOKEN = 'token';
    private const string QUERY_PARAM_EXPIRES_AT = 'expiresAt';
    private const string QUERY_PARAM_USER_EMAIL = 'email';
    private const string QUERY_PARAM_HASH = '_hash';
    private SignatureHasher $signature;

    public function __construct(
        private readonly UriSigner $uriSigner,
        PropertyAccessorInterface $propertyAccessor,
        private readonly int $lifetime,
        array $userSignatureProperties,
        #[\SensitiveParameter] string $secret,
    ) {
        $this->signature = new SignatureHasher($propertyAccessor, $userSignatureProperties, $secret);
    }

    /**
     * @throws SignatureParamsException
     */
    public function generateQueryParams(User $user): string
    {
        return $this->uriSigner->sign($this->getHttpQueryParams($this->getQueryParams($user)));
    }

    /**
     * @throws SignatureParamsException
     */
    private function getQueryParams(User $user): array
    {
        if (null === $user->getEmail()) {
            throw new SignatureParamsException(self::QUERY_PARAM_USER_EMAIL);
        }

        $expiresAt = $this->getExpiresAt();

        return [
            self::QUERY_PARAM_TOKEN => $this->signature->computeSignatureHash($user, $expiresAt),
            self::QUERY_PARAM_EXPIRES_AT => $expiresAt,
            self::QUERY_PARAM_USER_EMAIL => $user->getEmail(),
        ];
    }

    private function getHttpQueryParams(array $queryParams): string
    {
        return '?'.http_build_query($queryParams, '', '&');
    }

    private function getExpiresAt(): int
    {
        $now = time();

        return $now + $this->lifetime;
    }

    public function acceptSignatureHash(string $email, int $expiresAt, string $token): void
    {
        $this->signature->acceptSignatureHash($email, $expiresAt, $token);
    }

    public function verifySignatureHash(User $user, int $expires, string $hash): void
    {
        $this->signature->verifySignatureHash($user, $expires, $hash);
    }

    /**
     * @throws SignatureParamsException
     */
    public function acceptEmailVerifierDto(EmailVerifierVerifyDto $emailVerifierDto): void
    {
        $queryParams = [
            self::QUERY_PARAM_TOKEN => $emailVerifierDto->token,
            self::QUERY_PARAM_EXPIRES_AT => $emailVerifierDto->expiresAt,
            self::QUERY_PARAM_USER_EMAIL => $emailVerifierDto->email,
            self::QUERY_PARAM_HASH => $emailVerifierDto->_hash,
        ];

        if (!$this->uriSigner->check($this->getHttpQueryParams($queryParams))) {
            throw new SignatureParamsException(self::QUERY_PARAM_HASH);
        }
    }
}
