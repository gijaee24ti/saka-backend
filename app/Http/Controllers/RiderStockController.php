<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RiderStockController extends Controller
{
    public function outlet(Request $request): JsonResponse
    {
        return response()->json(['data' => $request->user()->outlet]);
    }

    public function index(Request $request): JsonResponse
    {
        $stocks = Stock::query()
            ->where('outlet_id', $request->user()->outlet_id)
            ->whereHas('menu', fn ($query) => $query->where('category', '!=', 'Literan'))
            ->with(['menu:id,name,category,image,status', 'outlet:id,branch,vehicle'])
            ->orderBy('menu_id')
            ->get();

        return response()->json(['data' => $stocks]);
    }

    public function update(Request $request, Stock $stock): JsonResponse
    {
        Gate::forUser($request->user())->authorize('updateAvailability', $stock);

        $validated = $request->validate([
            'stock_status' => ['required', 'in:Tersedia,Tidak Tersedia'],
        ]);

        $stock->update([
            'stock_status' => $validated['stock_status'],
            'rider_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Status ketersediaan berhasil diperbarui.',
            'data' => $stock->fresh()->load(['menu:id,name,category,image,status', 'outlet:id,branch,vehicle']),
        ]);
    }

    /**
     * Rider updates their own operational status (and syncs to outlet).
     */
    public function status(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'operational_status' => ['required', 'in:Berjualan,Istirahat,Tutup,Pindah,Tidak Beroperasi'],
        ]);

        $rider = $request->user();
        $rider->update(['operational_status' => $validated['operational_status']]);

        // Sync status to the rider's assigned outlet
        if ($rider->outlet) {
            $statusMap = [
                'Berjualan' => 'Aktif',
                'Istirahat' => 'Istirahat',
                'Tutup' => 'Tutup',
                'Pindah' => 'Pindah',
                'Tidak Beroperasi' => 'Tidak Beroperasi',
            ];
            $rider->outlet->update([
                'status' => $statusMap[$validated['operational_status']] ?? 'Tidak Beroperasi',
            ]);
        }

        return response()->json([
            'message' => 'Status operasional berhasil diperbarui.',
            'data' => $rider->fresh()->load('outlet'),
        ]);
    }
}
