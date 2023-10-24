<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;

class CharactersController extends Controller
{
    public function index()
    {
        $characters = Character::where('gender', 'Féminin')
        ->where('species', 'Human')
        ->get();


        return view('characters', compact('characters'));
    }
}
