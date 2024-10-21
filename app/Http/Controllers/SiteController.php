<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Admin\Language;
use App\Models\Admin\TopUpGame;
use App\Models\Admin\UsefulLink;
use App\Providers\Admin\BasicSettingsProvider;


class SiteController extends Controller
{
    public function index()
    {
        $page_title = "Home";
        $top_up_games = TopUpGame::where('status', 1)->latest()->limit(12)->get();
        return view('frontend.index', compact('page_title', 'top_up_games'));
    }
    public function topup()
    {
        $page_title = 'Top Up';
        return view('frontend.topup', compact('page_title'));
    }
    public function about()
    {
        $page_title = 'About';
        return view('frontend.about', compact('page_title'));
    }
    public function faq()
    {
        $page_title = 'Faq';
        return view('frontend.faq', compact('page_title'));
    }
    public function contact()
    {
        $page_title = 'contact';
        return view('frontend.contact', compact('page_title'));
    }

    public function blog()
    {
        $page_title = "Blog";
        $categories = BlogCategory::active()->latest()->get();
        $blogs = Blog::active()->orderBy('id', "DESC")->paginate(6);
        $recentPost = Blog::active()->latest()->limit(3)->get();
        return view('frontend.blog', compact('page_title', 'blogs', 'recentPost', 'categories'));
    }
    public function blogDetails($id, $slug)
    {

        $page_title = "Blog Details";
        $categories = BlogCategory::active()->latest()->get();
        $blog = Blog::where('id', $id)->where('slug', $slug)->first();
        $recentPost = Blog::active()->where('id', "!=", $id)->latest()->limit(3)->get();
        return view('frontend.blogDetails', compact('page_title', 'blog', 'recentPost', 'categories'));
    }
    public function blogByCategory($id, $slug)
    {
        $lang = selectedLang();
        $categories = BlogCategory::active()->latest()->get();
        $category = BlogCategory::findOrfail($id);
        $page_title = __('Category') . ' -' . ' ' . $category->data->language->$lang->name;
        $blogs = Blog::active()->where('category_id', $category->id)->latest()->paginate(8);
        $recentPost = Blog::active()->latest()->limit(3)->get();
        return view('frontend.blogByCategory', compact('page_title', 'blogs', 'category', 'categories', 'recentPost'));
    }
    public function changeLanguage($lang = null)
    {
        session()->put('local', $lang);
        return redirect()->back();
    }

    public function usefullLink($slug)
    {
        $useful_link = UsefulLink::where("slug", $slug)->first();
        if (!$useful_link) abort(404);
        $basic_settings = BasicSettingsProvider::get();
        $app_local = get_default_language_code();
        $page_title = $useful_link->title?->language?->$app_local?->title ?? $basic_settings->site_name;
        return view('frontend.pages.useful-link', compact('page_title', 'useful_link'));
    }
}
