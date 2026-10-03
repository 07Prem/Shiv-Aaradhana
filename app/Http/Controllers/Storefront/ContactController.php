<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\CmsSection;
use Illuminate\Contracts\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $contact = CmsSection::getByKey('contact_verified');

        return view('storefront.contact', compact('contact'));
    }
}
