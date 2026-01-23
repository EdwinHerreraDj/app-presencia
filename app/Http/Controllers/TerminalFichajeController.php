<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TerminalFichajeController extends Controller
{
    function index()
    {
        return view('terminal-fichajes.index');
    }
}
