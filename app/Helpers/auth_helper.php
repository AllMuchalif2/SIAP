<?php 
// app/Helpers/auth_helper.php

if (!function_exists('check_login')) {
    function check_login()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }
    }
}

if (!function_exists('check_role')) {
    function check_role($allowedRoles = [])
    {
        $session = session();
        $userRole = $session->get('role');
        
        if (!in_array($userRole, $allowedRoles)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}