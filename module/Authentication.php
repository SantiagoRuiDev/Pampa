<?php

namespace Module;


use Module\Token;


/**
 * AuthenticationHandler Class
 *
 * A class for handling user authentication processes.
 * Provides methods to create, encode, and decode tokens (e.g., JWT).
 * Includes functionality to hash passwords and compare hashed passwords for secure authentication.
 * Ideal for managing login, registration, and user validation securely.
 * 
 */
class Authentication
{
    // @ Replace the signature value for you own signature (Warning: Don't share your signature);
    private string $signature = "pampa-framework";

    private Token $token;

    public function __construct()
    {
        $this->token = new Token($this->signature);
    }

    /**
     * Set your secret signature for JWT
     *
     * @return void
     */
    public function setSignature(string $signature): void
    {
        $this->token->setSignature($signature);
        return;
    }


    /**
     * Encrypt your JWT and save it on Token Instance
     *
     * @return void
     */
    public function createToken(): void
    {
        $this->token->generate();
        return;
    }

    /**
     * Export the encoded the token and export it.
     *
     * @return string
     */
    public function exportToken(): string
    {
        return $this->token->export();
    }

    /**
     * Read a token inserted by client and get details.
     *
     * @param string $token The token to be scanned
     * @return mixed
     */
    public function decodeToken(string $token): mixed
    {
        return $this->token->scan($token);
    }

    /**
     * Set expiration time for your token
     *
     * @param int $seconds The hours/minutes to expire in seconds.
     * @return mixed
     */
    public function setTokenExpireDate(int $seconds): void
    {
      $this->token->setExpireDate($seconds);
      return;
    }

    /**
     * Modify or add new details in your token payload
     *
     * @param string $key Name of your stored value
     * @param mixed $value  Insert the data you want store in Payload
     * @return mixed
     */
    public function setTokenData(string $key, mixed $value): void
    {
        $this->token->setData($key, $value);
        return;
    }

    /**
     * Read a token and see if this is available or expired.
     *
     * @param string $token The token to be scanned
     * @return bool
     */
    public function verifyToken(string $token): bool
    {
        try {
            $information = $this->token->scan($token);
            if ($information) return true;
            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Convert the plain text in a hashed string
     *
     * @param string $password The password you want hash
     * @return string
     */
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Return boolean value if the password and hash comparation match.
     *
     * @param string $password The text you want compare
     * @param string $hash The hash you want compare
     * @return bool
     */
    public function comparePassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
