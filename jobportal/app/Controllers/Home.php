<?php

namespace App\Controllers;

class Home extends BaseController
{
    protected $helpers = ['url'];

    public function index(): string
    {
        return view('home/index');
    }

    public function jobs(): string
    {
        return view('home/jobs');
    }
}
