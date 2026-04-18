<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function show(): RedirectResponse
    {
        return redirect()->route('pages.show', ['slug' => 'kontak'], 301);
    }
}
