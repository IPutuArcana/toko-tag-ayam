<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data produk berhasil diambil',
            'data' => $products
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'selling_price' => 'required|numeric',
            'stock'         => 'required|integer',
            'category_id'   => 'required|integer',
            'description'   => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 400);
        }

        $product = Product::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product
        ], 201);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil dihapus',
            'data' => null
        ], 200);
    }
}