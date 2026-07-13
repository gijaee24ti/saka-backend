<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithPagination;
use App\Models\Menu;
use App\Models\Outlet;
use App\Models\Rider;
use App\Models\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    use InteractsWithPagination;

    private const DONUT_BRANCHES = [
        'OUTLET SAKA DAHLIA',
        'Cabang Stadion / Nagasakti',
        'Cabang Rumbai',
        'Cabang Hang Tuah Ujung',
    ];

    public function index(Request $request): JsonResponse
    {
        $stocks = Stock::query()
            ->with(['outlet', 'menu', 'rider'])
            ->when($request->filled('search'), fn ($query) => $query->whereHas('menu', fn ($menu) => $menu->where('name', 'like', '%'.$request->string('search').'%')))
            ->when($request->filled('outlet_id'), fn ($query) => $query->where('outlet_id', $request->integer('outlet_id')))
            ->when($request->filled('menu_id'), fn ($query) => $query->where('menu_id', $request->integer('menu_id')))
            ->when($request->filled('stock_status'), fn ($query) => $query->where('stock_status', $request->string('stock_status')))
            ->orderBy('id')
            ->paginate($this->perPage($request));

        return response()->json($stocks);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $this->assertBusinessRules($data);
        $stock = Stock::create($data);

        return response()->json(['message' => 'Ketersediaan berhasil ditambahkan.', 'data' => $stock->load(['outlet', 'menu', 'rider'])], 201);
    }

    public function show(Stock $stock): JsonResponse
    {
        return response()->json(['data' => $stock->load(['outlet', 'menu', 'rider'])]);
    }

    public function update(Request $request, Stock $stock): JsonResponse
    {
        $data = $this->validated($request, $stock);
        $complete = array_merge($stock->only(['outlet_id', 'menu_id', 'rider_id', 'stock_status', 'note']), $data);
        $this->assertBusinessRules($complete);
        $stock->update($data);

        return response()->json(['message' => 'Ketersediaan berhasil diperbarui.', 'data' => $stock->fresh()->load(['outlet', 'menu', 'rider'])]);
    }

    public function destroy(Stock $stock): JsonResponse
    {
        $stock->delete();

        return response()->json([], 204);
    }

    private function validated(Request $request, ?Stock $stock = null): array
    {
        $outletId = $request->input('outlet_id', $stock?->outlet_id);
        $required = $request->isMethod('patch') ? 'sometimes' : 'required';

        return $request->validate([
            'outlet_id' => [$required, 'exists:outlets,id'],
            'menu_id' => [
                $required,
                'exists:menus,id',
                Rule::unique('stocks', 'menu_id')->where(fn ($query) => $query->where('outlet_id', $outletId))->ignore($stock),
            ],
            'rider_id' => ['sometimes', 'nullable', 'exists:riders,id'],
            'stock_status' => ['sometimes', 'in:Tersedia,Tidak Tersedia'],
            'note' => ['sometimes', 'nullable', 'string', 'max:2000'],
        ]);
    }

    private function assertBusinessRules(array &$data): void
    {
        $outlet = Outlet::findOrFail($data['outlet_id']);
        $menu = Menu::findOrFail($data['menu_id']);

        if ($menu->category === 'Literan' && $outlet->branch !== 'OUTLET SAKA DAHLIA') {
            throw ValidationException::withMessages(['menu_id' => 'Produk literan hanya tersedia di OUTLET SAKA DAHLIA.']);
        }

        if (($menu->name === 'Donat' || $menu->category === 'Snack') && ! in_array($outlet->branch, self::DONUT_BRANCHES, true)) {
            throw ValidationException::withMessages(['menu_id' => 'Donat hanya tersedia di cabang yang ditentukan.']);
        }

        if (! empty($data['rider_id'])) {
            $rider = Rider::findOrFail($data['rider_id']);
            if ($rider->outlet_id !== $outlet->id) {
                throw ValidationException::withMessages(['rider_id' => 'Rider harus berasal dari outlet yang sama.']);
            }
        }
    }
}
