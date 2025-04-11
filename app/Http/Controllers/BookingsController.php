<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function index()
    {
        return view('bookings/list');

    }

    public function show()
    {
        return view('bookings/show');

    }

    public function create()
    {
        return view('bookings/create');
    }

    public function edit()
    {
        return view('bookings/create');
    }

    public function store()
    {

    }

    public function update($id)
    {

    }

    public function destroy($id)
    {

    }
}
