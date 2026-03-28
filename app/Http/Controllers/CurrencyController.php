<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function dataTable()
    {
        return Currency::all();
    }
    
    public function countries()
    {
        return Country::all();
    }
}
