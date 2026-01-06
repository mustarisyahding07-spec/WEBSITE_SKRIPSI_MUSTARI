<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('category')->latest()->paginate(9);
        return view('front.articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        return view('front.articles.show', compact('article'));
    }
}
