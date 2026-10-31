<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function beranda(): View
    {
        return view('public.beranda');
    }

    public function prestasi(): View
    {
        return view('public.prestasi');
    }

    public function samudraBiru(): View
    {
        return view('public.samudra-biru');
    }

    public function teknikBesi(): View
    {
        return view('public.teknik-besi');
    }

    public function tenagaAhli(): View
    {
        return view('public.tenaga-ahli');
    }

    public function workshop(): View
    {
        return view('public.workshop');
    }
}
