<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Make sure you've installed the Firebase JWT library via Composer
// composer require firebase/php-jwt

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MY_Jwt {
    
    private $key;
    private $algo;
    protected $CI;
    
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->key = 'Man12345';
        $this->algo = 'HS256';
    }
    
    public function encode($data, $exp = 3600)
    {
        $issuedAt = time();
        $payload = [
            'iat' => $issuedAt,
            'exp' => $issuedAt + $exp,
            'data' => $data
        ];
        return JWT::encode($payload, $this->key, $this->algo);
    }
    
    public function decode($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->key, $this->algo));
            return (array) $decoded;
        } catch (Exception $e) {
            return false;
        }
    }
}