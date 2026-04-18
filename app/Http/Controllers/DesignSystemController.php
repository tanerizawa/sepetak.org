<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DesignSystemController extends Controller
{
    public function __invoke(Request $request)
    {
        $themes = [
            'tani-soft' => 'Varian A — Tani Merah Soft',
            'kopi-kertas' => 'Varian B — Kopi & Kertas',
            'senja-modern' => 'Varian C — Senja Modern',
        ];

        $theme = $request->query('theme');
        $theme = array_key_exists($theme, $themes) ? $theme : 'tani-soft';

        return view('design-system.index', [
            'themes' => $themes,
            'theme' => $theme,
        ]);
    }
}
