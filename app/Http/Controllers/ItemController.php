<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{
   // In your controller
        public function index()
        {
            if(request()->ajax()) {
                return datatables()->of(Item::query())
                    ->addColumn('action', function($row) {
                        return '<div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-warning edit-item" data-id="'.$row->id.'" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-item" data-id="'.$row->id.'" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>';
                    })
                    ->addColumn('image', function($row) {
                        return $row->image ? '<img src="'.asset('storage/'.$row->image).'" alt="Image" width="50">' : 'N/A';
                    })
                    ->rawColumns(['action', 'image'])
                    ->addIndexColumn()
                    ->make(true);
            }

            return view('items.items');
        }

    public function getItems(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = Item::select('*');
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $btn = '<div class="d-flex justify-content-center gap-2">';
                        $btn .= '<a href="'.route('items.edit', $row->id).'" class="btn btn-sm btn-warning edit-item" data-id="'.$row->id.'" title="Edit"><i class="fas fa-edit"></i></a>';
                        $btn .= '<button class="btn btn-sm btn-danger delete-item" data-id="'.$row->id.'" title="Delete"><i class="fas fa-trash-alt"></i></button>';
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return response()->json(['error' => 'Invalid request'], 400);
        } catch (Exception $e) {
            Log::error('Failed to fetch items for DataTable: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load items data'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'item_code'     => 'required|string|max:255|unique:items,item_code',
                'item_name'     => 'required|string|max:255',
                'mfd_date'      => 'nullable|date',
                'exp_date'      => 'required|date|after_or_equal:today',
                'company_name'  => 'nullable|string|max:255',
                'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'quantity'      => 'required|integer|min:0',
                'cost_price'    => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
            ]);

            if ($request->hasFile('image')) {
                try {
                    $imagePath = $request->file('image')->store('uploads/items', 'public');
                    $validated['image'] = $imagePath;
                } catch (Exception $e) {
                    Log::error('Image upload failed: ' . $e->getMessage());
                    return redirect()->back()
                        ->with('error', 'Failed to upload image. Please try again.')
                        ->withInput();
                }
            }

            Item::create($validated);

            return redirect()->route('items.index')
                ->with('success', 'Item created successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            Log::error('Item creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create item. Please try again.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $item = Item::findOrFail($id);
            return view('items.edit', compact('item'));
        } catch (Exception $e) {
            Log::error('Failed to load edit form: ' . $e->getMessage());
            return redirect()->route('items.index')
                ->with('error', 'Failed to load item for editing. Please try again.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $item = Item::findOrFail($id);

            $validated = $request->validate([
                'item_code'     => 'required|string|max:255|unique:items,item_code,'.$id,
                'item_name'     => 'required|string|max:255',
                'mfd_date'      => 'nullable|date',
                'exp_date'      => 'required|date|after_or_equal:today',
                'company_name'  => 'nullable|string|max:255',
                'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'quantity'      => 'required|integer|min:0',
                'cost_price'    => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
            ]);

            if ($request->hasFile('image')) {
                try {
                    // Delete old image if exists
                    if ($item->image) {
                        Storage::disk('public')->delete($item->image);
                    }

                    $imagePath = $request->file('image')->store('uploads/items', 'public');
                    $validated['image'] = $imagePath;
                } catch (Exception $e) {
                    Log::error('Image upload failed: ' . $e->getMessage());
                    return redirect()->back()
                        ->with('error', 'Failed to upload image. Please try again.')
                        ->withInput();
                }
            }

            $item->update($validated);

            return redirect()->route('items.index')
                ->with('success', 'Item updated successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (Exception $e) {
            Log::error('Item update failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update item. Please try again.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = Item::findOrFail($id);

            // Delete image if exists
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully'
            ]);

        } catch (Exception $e) {
            Log::error('Item deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete item. Please try again.'
            ], 500);
        }
    }
}
