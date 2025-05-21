<?php
class Apid extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //$this->load->library('MY_Jwt');
        $this->load->model('Login_model');
        $this->load->library('TokenHandler');
    }
	public function index()
	{
		$this->load->view('welcome_message');
	}
    // public function login()
    // {
    //     $username = $this->input->post('username');
    //     $password = $this->input->post('password');
    //     $user = $this->Login_model->verify_login($username, $password);


    //     if ($user) {
    //         $token = $this->tokenhandler->generateToken(['id' => $user->id, 'username' => $user->username]);
    //         $this->Login_model->save_user_token($user->id, $token);
    //         $this->output
    //             ->set_content_type('application/json')
    //             ->set_output(json_encode(['token' => $token]));
    //     } else {
    //         $this->output
    //             ->set_status_header(401)
    //             ->set_output(json_encode(['error' => 'Invalid credentials']));
    //     }
    // }

    public function login()
    {
        // ✅ CORS Headers
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Methods: POST, OPTIONS");

        // ✅ Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // ✅ Continue with login logic
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $user = $this->Login_model->verify_login($username, $password);

        if ($user) {
            $token = $this->tokenhandler->generateToken([
                'id' => $user->id,
                'username' => $user->username
            ]);

            $this->Login_model->save_user_token($user->id, $token);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['token' => $token]));
        } else {
            $this->output
                ->set_status_header(401)
                ->set_output(json_encode(['error' => 'Invalid credentials']));
        }
    }


    public function protected_data()
    {
        $headers = $this->input->request_headers();
        if (!isset($headers['Authorization'])) {
            show_error('Missing token', 401);
        }

        $token = trim(str_replace('Bearer', '', $headers['Authorization']));
        $decoded = $this->tokenhandler->validateToken($token);

        if (!$decoded) {
            show_error('Invalid or expired token', 401);
        }
    }
    public function get_data() {
        $this->protected_data();
        $data = ['status' => 'success', 'message' => 'Secure data accessed'];
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
