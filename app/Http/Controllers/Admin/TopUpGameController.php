<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\Response;
use App\Models\Admin\TopUpGame;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TopUpGameController extends Controller
{
    function index()
    {
        $page_title = 'Top Up Game';
        $top_up_games = TopUpGame::latest()->get();
        return view('admin.sections.top-up-game.index', compact('top_up_games', 'page_title'));
    }
    function create()
    {
        $page_title = 'Create';
        return view('admin.sections.top-up-game.create', compact('page_title'));
    }
    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_image'      => 'required|image|mimes:png,jpg,jpeg,svg,webp',
            'cover_image'      => 'required|image|mimes: jpg,png,jpeg,svg,webp',
            'title'      => 'required|string',
            'description'      => 'required|string',
            'google_store'      => 'nullable',
            'apple_store'      => 'nullable',
            'name' => 'array',
            'name.*' => 'required',
            'label' => 'array',
            'label.*' => 'required',
            'type' => 'array',
            'type.*' => 'required',
            'credit_amount' => 'array',
            'credit_amount.*' => 'required',
            'currency_amount' => 'array',
            'currency_amount.*' => 'required',
        ], [
            'name.*.required' => 'Name is required',
            'label.*.required' => 'Label is required',
            'type.*.required' => 'Type is required',
            'currency_amount.*.required' => 'Currency amount is required',
            'credit_amount.*.required' => 'Credit amount amount is required',

        ]);


        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $validated = $validator->validate();

        if ($request->hasFile('profile_image')) {
            try {
                $image = get_files_from_fileholder($request, 'profile_image');
                $uploadFlag = upload_files_from_path_dynamic($image, 'top-up-game');
                $validated['profile_image'] = $uploadFlag;
            } catch (Exception $e) {
                return back()->withErrors($validator)->withInput()->with(['error' => ['Image file upload faild!']]);
            }
        }
        if ($request->hasFile('cover_image')) {
            try {
                $image = get_files_from_fileholder($request, 'cover_image');
                $uploadFlag = upload_files_from_path_dynamic($image, 'top-up-game');
                $validated['cover_image'] = $uploadFlag;
            } catch (Exception $e) {
                return back()->withErrors($validator)->withInput()->with(['error' => ['Image file upload faild!']]);
            }
        }
        //for player id
        $input_fields_player_id = [];
        foreach ($request->name ?? [] as $key => $item) {
            $input_fields_player_id[]       = [
                'name'                      => $item,
                'label'                     => $request->label[$key] ?? "",
                'required'                  => true,
                'type'                      => 'text'
            ];
        }
        //for Recharge id
        $input_fields_recharge = [];
        foreach ($request->credit_amount ?? [] as $key => $item) {
            $input_fields_recharge[]     = [
                'credit_amount'                 => $item,
                'type'                          => $request->type[$key] ?? $request->type[0],
                'currency_amount'               => $request->currency_amount[$key] ?? "",
            ];
        }

        $data = [
            'input_fields_player_id' => $input_fields_player_id,
            'input_fields_recharge' => $input_fields_recharge
        ];


        $validated['slug'] = Str::slug($validated['title']);
        $validated['input_fields'] = $data;



        try {
            TopUpGame::create($validated);
        } catch (Exception $e) {
            return back()->with(['error' => 'Something went wrong!, Please try again']);
        }

        return redirect()->route('admin.top.up.game.index')->with(['success' => ['Top Up Added Successfully']]);
    }

    function edit($slug)
    {
        $page_title = 'Edit';
        $top_up_game = TopUpGame::where('slug', $slug)->first();
        return view('admin.sections.top-up-game.edit', compact('page_title', 'top_up_game'));
    }
    function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'top_up_game_id' => 'required|numeric',
            'profile_image'      => 'nullable|image|mimes: jpg,png,jpeg,svg,webp',
            'cover_image'      => 'nullable|image|mimes: jpg,png,jpeg,svg,webp',
            'title'      => 'required|string',
            'description'      => 'required|string',
            'google_store'      => 'nullable',
            'apple_store'      => 'nullable',
            'name' => 'array',
            'name.*' => 'required',
            'label' => 'array',
            'label.*' => 'required',
            'type' => 'array',
            'type' => 'required',
            'credit_amount' => 'array',
            'credit_amount.*' => 'required',
            'currency_amount' => 'array',
            'currency_amount.*' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        //for player id
        $input_fields_player_id = [];
        foreach ($request->name ?? [] as $key => $item) {
            $input_fields_player_id[]       = [
                'name'                      => $item,
                'label'                     => $request->label[$key] ?? "",
                'required'                     => true,
                'type' => 'text',
            ];
        }
        //for Recharge id
        $input_fields_recharge = [];
        foreach ($request->credit_amount ?? [] as $key => $item) {
            $input_fields_recharge[]     = [
                'credit_amount'                 => $item,
                'type'                          => $request->type[$key] ?? $request->type[0],
                'currency_amount'               => $request->currency_amount[$key] ?? "",
            ];
        }

        $validated = $validator->validate();
        $data = [
            'input_fields_player_id' => $input_fields_player_id,
            'input_fields_recharge' => $input_fields_recharge
        ];
        $validated['input_fields'] = $data;

        if ($request->hasFile('profile_image')) {
            try {
                $image = get_files_from_fileholder($request, 'profile_image');
                $uploadFlag = upload_files_from_path_dynamic($image, 'top-up-game');
                $validated['profile_image'] = $uploadFlag;
            } catch (Exception $e) {
                return back()->withErrors($validator)->withInput()->with(['error' => ['Image file upload faild!']]);
            }
        }
        if ($request->hasFile('cover_image')) {
            try {
                $image = get_files_from_fileholder($request, 'cover_image');
                $uploadFlag = upload_files_from_path_dynamic($image, 'top-up-game');
                $validated['cover_image'] = $uploadFlag;
            } catch (Exception $e) {
                return back()->withErrors($validator)->withInput()->with(['error' => ['Image file upload faild!']]);
            }
        }
        $top_up_game_id = $validated['top_up_game_id'];
        $top_up_game = TopUpGame::findOrFail($top_up_game_id);
        try {
            $top_up_game->update($validated);
        } catch (Exception $e) {
            return back()->with(['error' => 'Something went wrong!, Please try again']);
        }

        return redirect()->route('admin.top.up.game.index')->with(['success' => ['Top Up Updated Successfully']]);
    }

    function delete(Request $request)
    {
        $target = $request->target;
        $top_up_game = TopUpGame::findOrFail($target);
        $validator = Validator::make($request->all(), [
            'target' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with(['error' => ['Something was wrong!']]);
        }

        $validated = $validator->validate();
        try {
            $top_up_game->delete();
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong!,Please try again']]);
        }

        return back()->with(['success' => ['Top Up Deleted Successfully']]);
    }

    public function statusUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'status'                    => 'required|boolean',
            'data_target'               => 'required|string',
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            $error = ['error' => $validator->errors()];
            return Response::error($error, null, 400);
        }
        $validated = $validator->validate();
        $item_id = $validated['data_target'];

        $payment_gateway = TopUpGame::find($item_id);
        if (!$payment_gateway) {
            $error = ['error' => ['Payment gateway not found!.']];
            return Response::error($error, null, 404);
        }

        try {
            $payment_gateway->update([
                'status' => ($validated['status'] == true) ? false : true,
            ]);
        } catch (Exception $e) {
            $error = ['error' => ['Something went worng!. Please try again.']];
            return Response::error($error, null, 500);
        }

        $success = ['success' => ['Payment gateway status updated successfully!']];
        return Response::success($success, null, 200);
    }
}
