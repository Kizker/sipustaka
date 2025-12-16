<?php

namespace App\Controllers;

class PwaController extends BaseController
{
    public function offline()
    {
        return view('pwa/offline', ['title' => 'Offline - SiPustaka']);
    }
}
