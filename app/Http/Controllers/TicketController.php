<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    // Menampilkan halaman e-ticket setelah sukses bayar
    public function index()
    {
        return view('ticket');
    }
}