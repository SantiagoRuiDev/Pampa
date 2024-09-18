<?php

namespace Module;

use Error;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Token Handler Class
 *
 * Create and manage JWT for Authentications.
 *
 * 
 */
class Token
{
    private string $signature;
    private array $payload;
    private string $hash = "";

    public function __construct(string $signature)
    {
        $this->signature = $signature;
        $this->payload = [
            'iss' => 'https://pampa-framework.com',
            'aud' => 'https://pampa-framework.com',
            'exp' => time() + 3600, // Expires in one hour
            'iat' => time(),
            'nbf' => time(),
            // 'id' => 1 // Store the user idenfitier
            // 'isAdmin' => true // Add any other detail to the token.
            // 'example' => 'pampa' // Add any other detail to the token.
        ];
    }

    /**
     * Generate the JWT and save it on Token.
     *
     * @return void
     */
    public function setSignature(string $signature): void
    {
        $this->signature = $signature;
    }

    /**
     * Generate the JWT and save it on Token.
     *
     * @return void
     */
    public function generate(): void
    {
        $this->hash = JWT::encode($this->payload, $this->signature, 'HS256');
    }

    /**
     * Get the generated hash from token and export it.
     *
     * @return string
     */
    public function export(): string
    {
        return $this->hash;
    }

    /**
     * Scan the token inserted in headers.
     *
     * @param string $token The token inserted by client.
     * @return mixed
     */
    public function scan(string $token): mixed
    {
        try {
            return JWT::decode($token, new Key($this->signature, 'HS256'));
        } catch (\Throwable $th) {
            return throw new Error('Invalid or expired token');
        }
    }

    /**
     * Set expiration time for your token
     *
     * @param int $seconds The hours/minutes to expire in seconds.
     * @return void
     */
    public function setExpireDate(int $seconds): void{
        try {
            $this->payload['exp'] = time() + $seconds;
            return;
        } catch (\Throwable $th) {
            return;
        }
    }

    /**
     * Set data in your token payload
     *
     * @param string $key Name of your stored value.
     * @param mixed $value Insert the data you want store in Payload.
     * @return void
     */
    public function setData(string $key, mixed $value): void{
        try {
            $this->payload[$key] = $value;
            return;
        } catch (\Throwable $th) {
            return;
        }
    }
}
