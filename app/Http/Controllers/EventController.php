<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    // Menampilkan halaman detail event
    public function show()
    {
        return view('event-detail');
    }

    // Menampilkan halaman checkout
    public function checkout()
    {
        return view('checkout');
    }
}