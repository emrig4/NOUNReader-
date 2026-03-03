<?php

namespace App\Modules\Page\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Theme;

class HomeController extends Controller
{
    //
    public function index (){
    	return view('pages.home');
    }
}
