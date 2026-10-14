<?php

namespace App\Http\Controllers;

use App\Support\StorefrontData;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function home(): View
    {
        return view('luxora.home', [
            'pageTitle' => 'Elevate your style. Live with intention.',
            'categories' => StorefrontData::categories(),
            'products' => StorefrontData::products(),
            'reviews' => StorefrontData::reviews(),
        ]);
    }

    public function shop(): View
    {
        return view('luxora.shop', [
            'pageTitle' => 'Shop Our Collection',
            'categories' => StorefrontData::categories(),
            'products' => StorefrontData::products(),
        ]);
    }

    public function about(): View
    {
        return view('luxora.about', [
            'pageTitle' => 'Passion for quality, focus on you.',
        ]);
    }
}
