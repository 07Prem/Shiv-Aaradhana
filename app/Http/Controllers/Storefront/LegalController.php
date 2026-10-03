<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class LegalController extends Controller
{
    public function privacy(): View
    {
        return view('storefront.legal.privacy');
    }

    public function terms(): View
    {
        return view('storefront.legal.terms');
    }
}
