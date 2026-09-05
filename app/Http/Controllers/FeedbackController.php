<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithPagination;
use App\Models\Feedback;
use App\Models\Outlet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    use InteractsWithPagination;

    public function index(Request $request): JsonResponse
    {
        $query = Feedback::query()
            ->with('outlet')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%'.$request->string('search').'%';
                $query->where(fn ($nested) => $nested
                    ->where('customer_name', 'like', $search)
                    ->orWhere('message', 'like', $search));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->latest('feedback_date')
            ->latest('id');

        if ($request->boolean('all')) {
            return response()->json(['data' => $query->get()]);
        }

        $feedback = $query->paginate($this->perPage($request));

        return response()->json($feedback);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $data['status'] ??= 'Pending';
        $data['feedback_date'] ??= now()->toDateString();
        $this->syncBranch($data);
        $feedback = Feedback::create($data);

        return response()->json(['message' => 'Feedback berhasil ditambahkan.', 'data' => $feedback->load('outlet')], 201);
    }

    public function show(Feedback $feedback): JsonResponse
    {
        return response()->json(['data' => $feedback->load('outlet')]);
    }

    public function update(Request $request, Feedback $feedback): JsonResponse
    {
        $data = $this->validated($request, true);
        $this->syncBranch($data);
        $feedback->update($data);

        return response()->json(['message' => 'Feedback berhasil diperbarui.', 'data' => $feedback->fresh()->load('outlet')]);
    }

    public function destroy(Feedback $feedback): JsonResponse
    {
        $feedback->delete();

        return response()->json([], 204);
    }

    private function validated(Request $request, bool $partial = false): array
    {
        $required = $partial || $request->isMethod('patch') ? 'sometimes' : 'required';

        return $request->validate([
            'outlet_id' => ['sometimes', 'nullable', 'exists:outlets,id'],
            'customer_name' => [$required, 'string', 'max:100'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'branch' => ['sometimes', 'nullable', 'string', 'max:255'],
            'type' => ['sometimes', 'in:Review,Masukan,Keluhan'],
            'category' => ['sometimes', 'string', 'max:100'],
            'rating' => ['sometimes', 'integer', 'between:1,5'],
            'message' => [$required, 'string', 'max:2000'],
            'status' => ['sometimes', 'in:Pending,Ditampilkan,Disembunyikan'],
            'feedback_date' => ['sometimes', 'date'],
        ]);
    }

    private function syncBranch(array &$data): void
    {
        if (array_key_exists('outlet_id', $data) && $data['outlet_id']) {
            $data['branch'] = Outlet::find($data['outlet_id'])?->branch;
        }
    }
}
