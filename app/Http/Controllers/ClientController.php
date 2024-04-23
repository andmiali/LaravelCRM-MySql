<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Team;
use App\Models\First;
use App\Models\About;
use App\Models\Portfolio;

class ClientController extends Controller
{
    public function client()
    {
        $services = Service::all();
        $teams = Team::all();
        $first = First::all();
        $about = About::all();
        $portfolio = Portfolio::all();
        return view('client.index', compact('services', 'teams', 'first', 'about', 'portfolio'));
    }
}
