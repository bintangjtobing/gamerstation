<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscribe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\User\SendMail;
use Illuminate\Support\Facades\Notification;


class SubscriberListController extends Controller
{
    function index()
    {
        $subscribers = Subscribe::get();
        $page_title = 'Subscribers';
        return view('admin.components.data-table.subscribers-table', compact('subscribers', 'page_title'));
    }
    public function subscriberMail(Request $request)
    {
        $request->validate([
            'subject'       => "required|string|max:250",
            'message'       => "required|string|max:2000",
        ]);

        $subscribers = Subscribe::get();



        try {
            Notification::send($subscribers, new SendMail((object) $request->all()));
        } catch (\Exception $e) {
            return back()->with(['error' => [__('Something went wrong! Please try again.')]]);
        }

        return back()->with(['success' => ['Email successfully sended']]);
    }
}
