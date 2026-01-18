<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'newsletter_id' => 'nullable|exists:newsletters,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Find default newsletter or specified one
        $newsletter = $request->newsletter_id 
            ? Newsletter::findOrFail($request->newsletter_id)
            : Newsletter::active()->defaultSignup()->first();

        if (!$newsletter) {
            return response()->json([
                'success' => false,
                'message' => 'No newsletter available'
            ], 404);
        }

        // Check if member exists
        $member = \App\Models\Member::where('email', $request->email)->first();

        if (!$member) {
            // Create new member
            $member = \App\Models\Member::create([
                'email' => $request->email,
                'name' => $request->input('name'),
                'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                'subscribed' => true,
            ]);
        }

        // Subscribe to newsletter
        $member->subscribeTo($newsletter);

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed to newsletter'
        ]);
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'newsletter_id' => 'required|exists:newsletters,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $member = \App\Models\Member::where('email', $request->email)->first();
        
        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found'
            ], 404);
        }

        $newsletter = Newsletter::findOrFail($request->newsletter_id);
        $member->unsubscribeFrom($newsletter);

        return response()->json([
            'success' => true,
            'message' => 'Successfully unsubscribed from newsletter'
        ]);
    }
}
