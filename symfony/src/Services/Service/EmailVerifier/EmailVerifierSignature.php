<?php

namespace App\Services\Service\EmailVerifier;

use App\Services\Service\EmailVerifier\Exception\SignatureParamsException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\UriSigner;

class EmailVerifierSignature
{
    private const string QUERY_PARAM_TOKEN = 'token';
    private const string QUERY_PARAM_EXPIRES_AT = 'expiresAt';
    private const string QUERY_PARAM_USER_EMAIL = 'email';
    private ?string $token = null;
    private ?int $expiresAt = null;
    private ?string $email;

    public function __construct(
        private readonly UriSigner $uriSigner,
    ) {
    }

    public function set(string $token, int $expiresAt, string $email): void
    {
        $this->token = $token;
        $this->expiresAt = $expiresAt;
        $this->email = $email;
    }

    /**
     * @throws SignatureParamsException
     */
    public function generateQueryParams(): string
    {
        return $this->uriSigner->sign('?'.$this->getHttpQueryParams());
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiresAt(): int
    {
        return $this->expiresAt;
    }

    /**
     * @throws SignatureParamsException
     */
    public function getQueryParams(): array
    {
        if (null === $this->token) {
            throw new SignatureParamsException(self::QUERY_PARAM_TOKEN);
        }

        if (null === $this->expiresAt) {
            throw new SignatureParamsException(self::QUERY_PARAM_EXPIRES_AT);
        }

        if (null === $this->email) {
            throw new SignatureParamsException(self::QUERY_PARAM_USER_EMAIL);
        }

        return [
            self::QUERY_PARAM_TOKEN => $this->token,
            self::QUERY_PARAM_EXPIRES_AT => $this->expiresAt,
            self::QUERY_PARAM_USER_EMAIL => $this->email,
        ];
    }

    /**
     * @throws SignatureParamsException
     */
    private function getHttpQueryParams(): string
    {
        return http_build_query($this->getQueryParams(), '', '&');
    }

    public function isExpired(Request $request): bool
    {
        return $request->query->getInt(self::QUERY_PARAM_EXPIRES_AT) <= time();
    }
}
