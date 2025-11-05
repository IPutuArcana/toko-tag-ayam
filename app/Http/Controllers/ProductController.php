<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- Penting untuk Gambar

class ProductController extends Controller
{
    // Menampilkan semua produk
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    // Menampilkan form tambah
    public function create()
    {
        $categories = Category::all();
        $materials = Material::all();
        return view('products.create', compact('categories', 'materials'));
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'materials' => 'required|array',
            'materials.*.id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|numeric|min:0.01',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $imagePath,
            'selling_price' => $request->selling_price,
            'stock' => $request->stock,
        ]);

        $recipeData = [];
        foreach ($request->materials as $material) {
            if ($material['quantity'] > 0) {
                $recipeData[$material['id']] = ['quantity_needed' => $material['quantity']];
            }
        }
        $product->materials()->attach($recipeData);

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit(Product $product)
    {
        $categories = Category::all();
        $materials = Material::all();
        $productRecipe = $product->materials->pluck('pivot.quantity_needed', 'id');

        return view('products.edit', compact('product', 'categories', 'materials', 'productRecipe'));
    }

    // Menyimpan data yang di-update
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'materials' => 'required|array',
            'materials.*.id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|numeric|min:0.01',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['_token', '_method', 'materials', 'image']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('product_images', 'public');
        }

        $product->update($data);

        $recipeData = [];
        foreach ($request->materials as $material) {
            if ($material['quantity'] > 0) {
                $recipeData[$material['id']] = ['quantity_needed' => $material['quantity']];
            }
        }
        $product->materials()->sync($recipeData);

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil diperbarui.');
    }

    // Menghapus data
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->materials()->detach();
        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil dihapus.');
    }
}