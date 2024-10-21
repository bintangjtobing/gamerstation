<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\Admin\TopUpGame;
use App\Http\Controllers\Controller;

class GameTopupController extends Controller
{
    function index()
    {
        $page_title = 'Top Up';
        $top_up_games = TopUpGame::where('status', 1)->latest()->paginate(12);
        return view('user.sections.game-topup-user.game-topup', compact('page_title', 'top_up_games'));
    }
}
