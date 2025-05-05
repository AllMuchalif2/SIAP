<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Asisten extends Controller
{
    public function __construct()
    {
        if (session()->get('role') !== 'asisten') {
            return redirect()->to('/login');
        }
    }
    
    public function index()
    {
        return view('user/dashboard', [
            'user' => session()
        ]);
    }
}