<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }
    
    public function loginPage()
    {
        return view('auth/login');
    }
    
    public function registerPage()
    {
        return view('auth/register');
    }
    
    public function dashboard()
    {
        return view('auth/dashboard');
    }
}