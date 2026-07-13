<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithPagination;
use App\Models\Rider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RiderController extends Controller
{
    use InteractsWithPagination;

    public function index(Request $request): JsonResponse
    {
        $riders = Rider::query()
            ->with('outlet')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%'.$request->string('search').'%';
                $query->where(fn ($nested) => $nested->where('name', 'like', $search)->orWhere('username', 'like', $search));
            })
            ->when($request->filled('account_status'), fn ($query) => $query->where('account_status', $request->string('account_status')))
            ->when($request->filled('operational_status'), fn ($query) => $query->where('operational_status', $request->string('operational_status')))
            ->orderBy('id')
            ->paginate($this->perPage($request));

        return response()->json($riders);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $data['password'] = Hash::make($data['password']);
        $rider = Rider::create($data);

        return response()->json(['message' => 'Rider berhasil ditambahkan.', 'data' => $rider->load('outlet')], 201);
    }

    public function show(Rider $rider): JsonResponse
    {
        return response()->json(['data' => $rider->load('outlet')]);
    }

    public function update(Request $request, Rider $rider): JsonResponse
    {
        $data = $this->validated($request, $rider);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $rider->update($data);

        return response()->json(['message' => 'Rider berhasil diperbarui.', 'data' => $rider->fresh()->load('outlet')]);
    }

    public function destroy(Rider $rider): JsonResponse
    {
        $rider->tokens()->delete();
        $rider->delete();

        return response()->json([], 204);
    }

    private function validated(Request $request, ?Rider $rider = null): array
    {
        $required = $request->isMethod('patch') ? 'sometimes' : 'required';
        $passwordRule = $rider ? ['sometimes', 'nullable', 'string', 'min:8', 'max:72'] : ['required', 'string', 'min:8', 'max:72'];

        return $request->validate([
            'outlet_id' => [$required, 'nullable', 'exists:outlets,id', Rule::unique('riders', 'outlet_id')->ignore($rider)],
            'name' => [$required, 'string', 'max:255'],
            'username' => [$required, 'string', 'max:100', Rule::unique('riders', 'username')->ignore($rider)],
            'password' => $passwordRule,
            'phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'account_status' => ['sometimes', 'in:Aktif,Tidak Aktif'],
            'operational_status' => ['sometimes', 'in:Berjualan,Istirahat,Tutup,Pindah'],
            'note' => ['sometimes', 'nullable', 'string', 'max:2000'],
        ]);
    }
}
