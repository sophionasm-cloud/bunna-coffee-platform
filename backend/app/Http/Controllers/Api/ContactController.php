<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $message = Message::create($data);

        // Send confirmation email (graceful fail)
        try {
            Mail::to($data['email'])->send(new \App\Mail\ContactConfirmation($data));
        } catch (\Exception $e) {
            logger()->warning('Contact email failed: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Thank you for contacting BUNNA. We will respond within 24-48 hours.',
            'id'      => $message->id,
        ], 201);
    }
}