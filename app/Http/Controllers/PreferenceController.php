<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePreferenceRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PreferenceController extends Controller
{
    public function index(): View
    {
        $theme  = session('theme', 'light');
        $locale = session('locale', 'fr');

        return view('preferences.index', compact('theme', 'locale'));
    }

    public function store(StorePreferenceRequest $request): RedirectResponse
    {
        session([
            'theme'  => $request->theme,
            'locale' => $request->locale,
        ]);

        return redirect()->route('preferences.index')->with('success', 'Préférences enregistrées.');
    }
}
