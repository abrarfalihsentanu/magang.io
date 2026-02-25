<?php

namespace App\Controllers;

class LandingController extends BaseController
{
    public function index()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('landing/index');
    }
}
