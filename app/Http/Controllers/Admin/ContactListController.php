<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Notifications\User\ContactMail;
use Exception;
use Illuminate\Support\Facades\Validator;


class ContactListController extends Controller
{
    function index()
    {
        $page_title = 'Contacts';
        $contacts = Message::get();
        return view('admin.components.data-table.contact-list-table', compact('contacts', 'page_title'));
    }
    function contactSendMail(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'contact_id' => 'required|integer',
            'subject' => 'nullable|string',
            'message' => "required|string"
        ])->validate();

        $contact_id = $validated['contact_id'];
        $contact = Message::findOrFail($contact_id);
        try {
            $contact->notify(new ContactMail((object)$validated));
        } catch (Exception $e) {
            return back()->with(['error' => ['Something went wrong!. Please try again']]);
        }


        return back()->with(['success' => ['Reply Send Successfully']]);
    }
}
