<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome ayu');
        return view('Selamat Datang, Muhammad Farhan');
        return view('Selamat Datang, Prodi Manajemen Informatika');
    }
}
