<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class Jwt
{
    private $key;
    private $algo;

    public function __construct()
    {
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
