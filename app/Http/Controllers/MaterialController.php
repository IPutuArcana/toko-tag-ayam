<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materials = Material::latest()->paginate(10);
        return view('materials.index', compact('materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:materials',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50', // misal: 'lembar', 'pcs'
            'cost_price' => 'required|numeric|min:0', // harga modal
        ]);

        Material::create($request->all());

        return redirect()->route('materials.index')
                         ->with('success', 'Bahan Baku berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('materials.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:materials,name,'.$material->id,
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'cost_price' => 'required|numeric|min:0',
        ]);

        $material->update($request->all());

        return redirect()->route('materials.index')
                         ->with('success', 'Bahan Baku berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $material->delete();

        return redirect()->route('materials.index')
                         ->with('success', 'Bahan Baku berhasil dihapus.');
    }
}
