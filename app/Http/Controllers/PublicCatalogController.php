<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Menu;
use App\Models\Outlet;
use App\Models\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    public function menus(Request $request): JsonResponse
    {
        $menus = Menu::query()
            ->where('status', 'Aktif')
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $menus]);
    }

    public function outlets(): JsonResponse
    {
        $outlets = Outlet::query()
            ->select(['id', 'branch', 'vehicle', 'open_time', 'close_time', 'status', 'address', 'maps_link', 'note'])
            ->orderBy('branch')
            ->get();

        return response()->json(['data' => $outlets]);
    }

    public function stocks(): JsonResponse
    {
        $stocks = Stock::query()
            ->select(['id', 'outlet_id', 'menu_id', 'stock_status', 'updated_at'])
            ->with(['menu:id,name,category,status'])
            ->orderBy('outlet_id')
            ->get();

        return response()->json(['data' => $stocks]);
    }

    public function feedback(): JsonResponse
    {
        $feedback = Feedback::query()
            ->where('status', 'Ditampilkan')
            ->select([
    'id',
    'customer_name',
    'branch',
    'type',
    'category',
    'rating',
    'message',
    'status',
    'feedback_date'
])
            ->latest('feedback_date')
            ->limit(50)
            ->get();

        return response()->json(['data' => $feedback]);
    }
}
