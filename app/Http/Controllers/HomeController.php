<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function indexPage(){

    	$binding = [
    		'title' => '首頁'
    	];
    	return view('welcome',$binding);
    }
}
