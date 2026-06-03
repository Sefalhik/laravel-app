<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterRequest;
use App\Jobs\SendNewsletterJob;
use App\Models\Newsletter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    public function index(): View
    {
        $newsletters = Newsletter::latest()->get();

        return view('newsletters.index', compact('newsletters'));
    }

    public function create(): View
    {
        return view('newsletters.create');
    }

    public function store(StoreNewsletterRequest $request): RedirectResponse
    {
        $newsletter = Newsletter::create($request->validated());

        SendNewsletterJob::dispatch($newsletter->id, $request->user()->id);

        return redirect()->route('newsletters.index')
            ->with('success', "Newsletter en file d'attente — le statut passera à « Envoyée » après le worker.");
    }
}
