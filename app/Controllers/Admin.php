<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Admin extends BaseController
{
    public function __construct()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }
    }

    public function index()
    {
        return view('admin/dashboard',[
            'user'=>session()
        ]);
    }
}