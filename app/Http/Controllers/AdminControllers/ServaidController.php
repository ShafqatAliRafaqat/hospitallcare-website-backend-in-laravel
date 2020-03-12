<?php

namespace App\Http\Controllers\AdminControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServaidController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $servaid_orders = array();
        return view('adminpanel.servaid.index', compact('servaid_orders'));
    }
}
