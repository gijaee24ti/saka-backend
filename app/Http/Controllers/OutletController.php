<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithPagination;
use App\Models\Outlet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OutletController extends Controller
{
    use InteractsWithPagination;

    public function index(Request $request): JsonResponse
    {
        $query = Outlet::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%'.$request->string('search').'%';
                $query->where(fn ($nested) => $nested->where('branch', 'like', $search)->orWhere('address', 'like', $search));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')));

        if ($request->boolean('all')) {
            return response()->json(['data' => $query->orderBy('id')->get()]);
        }

        $outlets = $query
            ->orderBy('id')
            ->paginate($this->perPage($request));

        return response()->json($outlets);
    }

    public function store(Request $request): JsonResponse
    {
        $outlet = Outlet::create($this->validated($request));

        return response()->json(['message' => 'Outlet berhasil ditambahkan.', 'data' => $outlet], 201);
    }

    public function show(Outlet $outlet): JsonResponse
    {
        return response()->json(['data' => $outlet]);
    }

    public function update(Request $request, Outlet $outlet): JsonResponse
    {
        $outlet->update($this->validated($request, $outlet));

        return response()->json(['message' => 'Outlet berhasil diperbarui.', 'data' => $outlet->fresh()]);
    }

    public function destroy(Outlet $outlet): JsonResponse
    {
        $outlet->delete();

        return response()->json([], 204);
    }

    private function validated(Request $request, ?Outlet $outlet = null): array
    {
        $required = $request->isMethod('patch') ? 'sometimes' : 'required';

        if ($request->has('maps_link')) {
            $request->merge([
                'maps_link' => $this->sanitizeMapsLink($request->input('maps_link')),
            ]);
        }

        return $request->validate([
            'branch' => [$required, 'string', 'max:255', Rule::unique('outlets', 'branch')->ignore($outlet)],
            'vehicle' => ['sometimes', 'nullable', 'string', 'max:100'],
            'open_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'close_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'status' => ['sometimes', 'in:Aktif,Tidak Aktif,Istirahat,Tutup,Pindah,Tidak Beroperasi,Beroperasi'],
            'address' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'maps_link' => ['sometimes', 'nullable', 'string', 'max:2048'],
            'note' => ['sometimes', 'nullable', 'string', 'max:3000'],
        ]);
    }

    private function sanitizeMapsLink(?string $url): ?string
    {
        if (empty($url) || trim($url) === '' || trim($url) === '-') {
            return null;
        }

        $link = trim($url);

        if (preg_match('/src=["\']([^"\']+)["\']/i', $link, $matches)) {
            $link = $matches[1];
        }

        if (!preg_match('/^https?:\/\//i', $link)) {
            $link = 'https://' . $link;
        }

        return $link;
    }
}
