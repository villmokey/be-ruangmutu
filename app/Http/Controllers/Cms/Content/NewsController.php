<?php

namespace App\Http\Controllers\Cms\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        return view("news.index");
    }

    public function create()
    {
        return view("news.create");
    }
}
