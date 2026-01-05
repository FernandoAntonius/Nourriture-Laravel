<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the contact us messages.
     */
    public function index()
    {
        $contacts = ContactUs::all();
        return response()->json($contacts);
    }

    /**
     * Store a newly created contact us message in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string',
        ]);

        $contact = ContactUs::create($validated);

        return response()->json([
            'message' => 'Contact message created successfully',
            'data' => $contact,
        ], 201);
    }

    /**
     * Display the specified contact us message.
     */
    public function show($id)
    {
        $contact = ContactUs::find($id);

        if (!$contact) {
            return response()->json([
                'message' => 'Contact not found',
            ], 404);
        }

        return response()->json($contact);
    }

    /**
     * Update the specified contact us message in storage.
     */
    public function update(Request $request, $id)
    {
        $contact = ContactUs::find($id);

        if (!$contact) {
            return response()->json([
                'message' => 'Contact not found',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'content' => 'sometimes|string',
        ]);

        $contact->update($validated);

        return response()->json([
            'message' => 'Contact updated successfully',
            'data' => $contact,
        ]);
    }

    /**
     * Delete the specified contact us message from storage.
     */
    public function destroy($id)
    {
        $contact = ContactUs::find($id);

        if (!$contact) {
            return response()->json([
                'message' => 'Contact not found',
            ], 404);
        }

        $contact->delete();

        return response()->json([
            'message' => 'Contact deleted successfully',
        ]);
    }
}
