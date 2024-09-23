<?php


namespace Module;

/**
 * CookieManager Class
 *
 * This class provides methods to manage browser cookies efficiently.
 * It allows you to create, retrieve, and delete cookies, as well as handle user sessions.
 * Use it to store and access user preferences, authentication tokens, and more.
 * 
 */
class Cookie {
    private string $name;
    private string $path;
    private mixed $value;
    private int $expiration;
    private string $domain;
    private bool $secure;
    private bool $httpOnly;

    public function __construct() {
        $this->path = "/";
        $this->expiration = 1;
        $this->domain = "localhost";
        $this->secure = false;
        $this->httpOnly = true;
    }

    public function initialize(): void {
        setcookie($this->name, $this->value, $this->expiration, $this->path, $this->domain, $this->secure, $this->httpOnly);
    }

    public function setDomain(string $domain): void {
        if(!is_null($domain)){
            $this->domain = $domain;
        }
    }

    public function getDomain(): string {
        return $this->domain;
    }

    public function setSecure(): void {
        $this->secure = true;
    }
    
    public function setHttpOnly(): void {
        $this->httpOnly = true;
    }

    public function setExpiration(int $expiration): void {
        if($expiration > 0){
            $this->expiration = time() + $expiration;
            return;
        }
        $this->expiration = 0;
    }

    public function setName(string $name): void {
        if(!is_null($name)){
            $this->name = $name;
        }
    }

    public function getName(): string {
        return $this->name;
    }

    
    public function setPath(string $path): void {
        if(!is_null($path)){
            $this->path = $path;
        }
    }

    public function getPath(): string {
        return $this->path;
    }


    public function setValue(mixed $value): void {
        if(!is_null($value)){
            $this->value = $value;
        }
    }

    public function getValue(): mixed {
        return $this->value;
    }

    public static function fetch($name): mixed {
        if(isset($_COOKIE[$name])){
            return $_COOKIE[$name];
        }else {
            return null;
        }
    }
}