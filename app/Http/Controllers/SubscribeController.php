<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscribeController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            Subscribe::create([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        } catch (Exception $e) {
            return back()->with(['error' => ['Somthing was wrong!']]);
        }

        return back()->with(['success' => ['Added successfully']]);
    }
}
