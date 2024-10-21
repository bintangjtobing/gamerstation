<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function message(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            Message::create([
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Somthing was wrong!']]);
        }

        return back()->with(['success' => ['Added successfully']]);
    }
}
