<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index() // function untuk menampilkan view
    {
        $data = [
            'title' => 'Item',
            'url_json' => url('/items/get_data'),
            'url' => url('/items'),
        ];
        return view('item', $data);
    }

    public function getData() // function untuk mengambil data melalui json
    {
        return response()->json([
            'status' => true,
            'data' => Item::all(),
            'message' => 'Data berhasil ditemukan',
        ], 200);
    }

    public function storeData(Request $request)
    {
        $data = $request->only(['item_name', 'status']);

        $validator = Validator::make($data, [
            'item_name' => ['required', 'unique:items', 'min:3', 'max:255'],
            'status' => ['required', 'in:1,0'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        Item::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil ditambahkan',
        ], 201);
    }

    public function show($id) // function untuk mengambil data berdasarkan id (untuk Edit)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $item,
            'message' => 'Data berhasil ditemukan',
        ], 200);
    }

    public function updateData(Request $request, $id) // function untuk mengubah data
    {
        $item = Item::where('id', $id)->first();

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $data = $request->only(['item_name', 'status']);

        $validator = Validator::make($data, [
            'item_name' => 'required|min:3|unique:items,item_name,' . $item->id,
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        $item->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diubah',
        ], 200);
    }

    public function destroyData($idItem) // function untuk menghapus data
    {
        $item = Item::where('id', $idItem)->first();

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus',
        ], 200);
    }
}