<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function __construct()
    {
        helper('auth');
        check_login();
        check_role(['asisten','admin' ]);
    }

    public function index()
    {
        return view('dashboard');
    }
}
