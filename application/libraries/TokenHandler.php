<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenHandler {
    private $key = 'Man@12345'; // Change this!
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Login_model');
    }

    public function generateToken($data) {
        $payload = [
            'iat' => time(),
            'exp' => time() + 3600,
            'data' => $data
        ];
        return JWT::encode($payload, $this->key, 'HS256');
    }

    public function validateToken($token) {
        try {
           // return JWT::decode($token, new Key($this->key, 'HS256'));
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            $user_id = $decoded->data->id;

            $latestToken = $this->CI->Login_model->get_user_token($user_id);
            if ($token !== $latestToken) {
                return false; // Old token
            }

            return $decoded->data;
        } catch (Exception $e) {
            return false;
        }
    }
}
