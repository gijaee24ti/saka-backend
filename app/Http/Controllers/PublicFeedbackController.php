<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Outlet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicFeedbackController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'outlet_id' => ['nullable', 'exists:outlets,id'],
            'customer_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'branch' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:Review,Masukan,Keluhan'],
            'category' => ['required', 'string', 'max:100'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        if (! empty($validated['outlet_id'])) {
            $validated['branch'] = Outlet::find($validated['outlet_id'])?->branch;
        }

        $feedback = Feedback::create([
            ...$validated,
            'status' => 'Pending',
            'feedback_date' => now()->toDateString(),
        ]);

        return response()->json([
            'message' => 'Feedback berhasil dikirim dan menunggu moderasi admin.',
            'data' => ['id' => $feedback->id, 'status' => $feedback->status],
        ], 201);
    }
}
