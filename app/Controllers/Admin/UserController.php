<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form']);
    }

    public function index()
    {
        $data['users'] = $this->userModel->findAll();
        return view('admin/users/index', $data);
    }

    public function new()
    {
        return view('admin/users/new');
    }


    public function create()
    {
        $rules = [
            'nama_lengkap' => 'required|min_length[3]',
            'username'     => 'required|min_length[4]|is_unique[tbl_users.username]',
            'password'     => 'required|min_length[6]',
            'role'         => 'required|in_list[admin,kasir]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/users/new')->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->save([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'password'     => $this->request->getPost('password'),
            'role'         => $this->request->getPost('role'),
        ]);

        return redirect()->to('/admin/users')->with('sukses', 'User baru berhasil ditambahkan.');
    }


    public function edit($id_user = null)
    {
        $data['user'] = $this->userModel->find($id_user);
        if (empty($data['user'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }
        return view('admin/users/edit', $data);
    }


    public function update($id_user = null)
    {
        $rules = [
            'nama_lengkap' => 'required|min_length[3]',
            'username'     => "required|min_length[4]|is_unique[tbl_users.username,id_user,$id_user]",
            'role'         => 'required|in_list[admin,kasir]'
        ];

        $password = $this->request->getPost('password');

        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/users/edit/' . $id_user)->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataUpdate = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'role'         => $this->request->getPost('role'),
        ];

        if (!empty($password)) {
            $dataUpdate['password'] = $password;
        }

        $this->userModel->update($id_user, $dataUpdate);

        return redirect()->to('/admin/users')->with('sukses', 'Data user berhasil diperbarui.');
    }

    public function delete($id_user = null)
    {
        if ($id_user == session()->get('id_user')) {
             return redirect()->to('/admin/users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $this->userModel->delete($id_user);
        return redirect()->to('/admin/users')->with('sukses', 'User berhasil dihapus.');
    }
}