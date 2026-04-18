<?php

namespace App\Http\Controllers;

use Database\Seeders\KontakPageContent;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Halaman kontak publik kanonik di /kontak (bukan CMS Page; isi naskah dari KontakPageContent).
     */
    public function show(): View
    {
        return view('contact.show', [
            'detailBody' => KontakPageContent::body(),
        ]);
    }
}
