<?php

class Login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	public function verify_login($username,$password)
	{
        $this->db->where('username',$username);
        $this->db->where('password',$password);
        $query =$this->db->get('user');
        return $query->row();
	}

    public function save_user_token($user_id, $token)
    {
        // Remove old token if any
        $this->db->where('user_id', $user_id);
        $this->db->delete('user_tokens');

        // Insert new token
        $this->db->insert('user_tokens', [
            'user_id' => $user_id,
            'token' => $token
        ]);
    }

    public function get_user_token($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_tokens');
        $row = $query->row();
        return $row ? $row->token : null;
    }
}