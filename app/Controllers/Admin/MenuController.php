<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuModel;

class MenuController extends BaseController
{
    protected $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
        helper(['form', 'url']); 
    }

    public function index()
    {
        $data['menu'] = $this->menuModel->findAll();
        return view('admin/menu/index', $data);
    }

    public function new()
    {
        return view('admin/menu/new');
    }

    public function create()
    {
        $rules = [
            'nama_menu' => 'required|min_length[3]|max_length[100]',
            'harga'     => 'required|numeric',
            'kategori'  => 'required|in_list[makanan,minuman,snack]',
            'gambar'    => 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/menu/new')->withInput()->with('errors', $this->validator->getErrors());
        }

        $fileGambar = $this->request->getFile('gambar');
        $namaGambar = $fileGambar->getRandomName();
        $fileGambar->move('uploads/menu', $namaGambar);

        $this->menuModel->save([
            'nama_menu' => $this->request->getPost('nama_menu'),
            'harga'     => $this->request->getPost('harga'),
            'kategori'  => $this->request->getPost('kategori'),
            'status'    => $this->request->getPost('status'),
            'gambar'    => $namaGambar,
        ]);

        return redirect()->to('/admin/menu')->with('sukses', 'Menu baru berhasil ditambahkan.');
    }

    public function edit($id_menu = null)
    {
        $data['menu'] = $this->menuModel->find($id_menu);
        if (empty($data['menu'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Menu tidak ditemukan');
        }
        return view('admin/menu/edit', $data);
    }


    public function update($id_menu = null)
    {
        $rules = [
            'nama_menu' => 'required|min_length[3]|max_length[100]',
            'harga'     => 'required|numeric',
            'kategori'  => 'required|in_list[makanan,minuman,snack]',
        ];

        $fileGambar = $this->request->getFile('gambar');
        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
             $rules['gambar'] = 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]';
        }

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/menu/edit/' . $id_menu)->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataUpdate = [
            'nama_menu' => $this->request->getPost('nama_menu'),
            'harga'     => $this->request->getPost('harga'),
            'kategori'  => $this->request->getPost('kategori'),
            'status'    => $this->request->getPost('status'),
        ];

        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $menuLama = $this->menuModel->find($id_menu);
            $gambarLama = $menuLama['gambar'];

            if (file_exists('uploads/menu/' . $gambarLama) && $gambarLama) {
                unlink('uploads/menu/' . $gambarLama);
            }
            
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/menu', $namaGambar);
            $dataUpdate['gambar'] = $namaGambar;
        }

        $this->menuModel->update($id_menu, $dataUpdate);

        return redirect()->to('/admin/menu')->with('sukses', 'Menu berhasil diperbarui.');
    }

    public function delete($id_menu = null)
    {
        $menu = $this->menuModel->find($id_menu);
        if ($menu) {
            $gambar = $menu['gambar'];
            if (file_exists('uploads/menu/' . $gambar) && $gambar) {
                unlink('uploads/menu/' . $gambar);
            }

            $this->menuModel->delete($id_menu);
            
            return redirect()->to('/admin/menu')->with('sukses', 'Menu berhasil dihapus.');
        } else {
            return redirect()->to('/admin/menu')->with('error', 'Menu tidak ditemukan.');
        }
    }
}