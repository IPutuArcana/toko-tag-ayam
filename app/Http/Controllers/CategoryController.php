<?php

namespace App\Http\Controllers;

use App\Models\Category; // <-- PENTING
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil data terbaru dan kirim ke view
        $categories = Category::latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya tampilkan view form
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        // Buat data baru
        Category::create($request->all());

        // Kembali ke halaman index dengan pesan sukses
        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Tidak kita pakai, biarkan saja
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category) // <-- Perhatikan, kita pakai $category
    {
        // Kirim data kategori yang mau diedit ke view form
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category) // <-- Perhatikan, kita pakai $category
    {
        // Validasi
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
        ]);

        // Update datanya
        $category->update($request->all());

        // Kembali ke index dengan pesan sukses
        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category) // <-- Perhatikan, kita pakai $category
    {
        // Hapus data
        $category->delete();

        // Kembali ke index dengan pesan sukses
        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil dihapus.');
    }
}