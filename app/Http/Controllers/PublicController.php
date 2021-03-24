<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    //* Index (Vue router mode history)
    public function clue(){
        return view('welcome');
    }
}
