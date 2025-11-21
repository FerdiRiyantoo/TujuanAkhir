<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('Selamat Datang, Muhammad Farhan');
    }
}
