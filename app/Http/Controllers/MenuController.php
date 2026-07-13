<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithPagination;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    use InteractsWithPagination;

    public function index(Request $request): JsonResponse
    {
        $menus = Menu::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%'.$request->string('search').'%';
                $query->where(fn ($nested) => $nested
                    ->where('name', 'like', $search)
                    ->orWhere('description', 'like', $search));
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->orderBy('id')
            ->paginate($this->perPage($request));

        return response()->json($menus);
    }

    public function store(Request $request): JsonResponse
    {
        $menu = Menu::create($this->validated($request));

        return response()->json(['message' => 'Menu berhasil ditambahkan.', 'data' => $menu], 201);
    }

    public function show(Menu $menu): JsonResponse
    {
        return response()->json(['data' => $menu]);
    }

    public function update(Request $request, Menu $menu): JsonResponse
    {
        $menu->update($this->validated($request, $menu));

        return response()->json(['message' => 'Menu berhasil diperbarui.', 'data' => $menu->fresh()]);
    }

    public function destroy(Menu $menu): JsonResponse
    {
        $menu->delete();

        return response()->json([], 204);
    }

    private function validated(Request $request, ?Menu $menu = null): array
    {
        $required = $request->isMethod('patch') ? 'sometimes' : 'required';

        return $request->validate([
            'name' => [$required, 'string', 'max:255', Rule::unique('menus', 'name')->ignore($menu)],
            'category' => [$required, 'in:Cup Series,Literan,Snack'],
            'cup_price' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'price_500' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'price_1l' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'description' => ['sometimes', 'nullable', 'string', 'max:3000'],
            'durability' => ['sometimes', 'nullable', 'string', 'max:3000'],
            'image' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'in:Aktif,Tidak Aktif'],
        ]);
    }
}
