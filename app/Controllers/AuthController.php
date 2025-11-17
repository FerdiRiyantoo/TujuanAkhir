<?php namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form']);
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        return view('auth/login');
    }

    public function loginProcess()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
            'role'     => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/login')->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = (string) $this->request->getPost('password');
        $role     = $this->request->getPost('role');

        $user = $this->userModel->where('username', $username)->first();

        if ($user &&
            $user['role'] === $role &&
            password_verify($password, $user['password'])) {

            $sessionData = [
                'id_user'      => $user['id_user'],
                'username'     => $user['username'],
                'nama_lengkap' => $user['nama_lengkap'],
                'role'         => $user['role'],
                'isLoggedIn'   => true
            ];

            $this->session->set($sessionData);

            if ($role === 'admin') {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/kasir/dashboard');
            }

        } else {
            return redirect()->to('/login')->with('error', 'Username, Password, atau Role salah');
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
