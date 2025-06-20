<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class PosController extends Controller
{
    public function index()
    {
        // Get unique company names for the dropdown
        $companies = Item::select('company_name')
                        ->distinct()
                        ->orderBy('company_name')
                        ->pluck('company_name');

        return view('pos.posDashboard', compact('companies'));
    }

    public function searchItems(Request $request)
    {
        $query = Item::query();

        if ($request->item_code) {
            $query->where('item_code', 'like', '%' . $request->item_code . '%');
        }

        if ($request->item_name) {
            $query->where('item_name', 'like', '%' . $request->item_name . '%');
        }

        if ($request->company_name) {
            $query->where('company_name', $request->company_name);
        }

        $items = $query->get(['id', 'item_code', 'item_name', 'company_name', 'selling_price']);

        return response()->json(['items' => $items]);
    }

    public function getItemByCode(Request $request)
    {
        $item = Item::where('item_code', $request->item_code)
                   ->first(['id', 'item_code as code', 'item_name as name', 'company_name as company', 'selling_price as price']);

        return response()->json(['item' => $item]);
    }
}
