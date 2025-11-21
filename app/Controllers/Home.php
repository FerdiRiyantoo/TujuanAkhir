<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_Andri');
         return view('welcome ayu');
        return view('welcome rizkanabila');
        return view('Ferdi');
        return view('welcome ayu');
        return view('Selamat Datang, Muhammad Farhan');
        return view('Selamat Datang, Prodi Manajemen Informatika');
    }
    public function about(): string 
    {
        $nama = 'Kelompok Absurd';
        return view('about');
    }
}
