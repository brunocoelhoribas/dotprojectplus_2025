<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller {
    public function switch($locale): RedirectResponse {
        if (in_array($locale, ['en', 'pt_BR', 'es'])) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
