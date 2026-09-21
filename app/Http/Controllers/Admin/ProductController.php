<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('read-products');
        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create-products');
        $categories = category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('read-products');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'min_stock' => 'required|integer|min:0',

        ]);
        $product = Product::create($data);

        session()->flash('swal',[
            'icon' => 'success',
            'title' => '¡Bien Hecho!',
            'text' => 'El producto se ha creado correctamente.'
        ]);
        return redirect()->route('admin.products.index',$product);
    }

    public function edit(Product $product)
    {
        Gate::authorize('update-products');

        $categories = category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        Gate::authorize('update-products');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'min_stock' => 'required|integer|min:0',
        ]);
        $product->update($data);

        session()->flash('swal',[
            'icon' => 'success',
            'title' => '¡Bien Hecho!',
            'text' => 'El producto se ha actualizado correctamente.'
        ]);
        return redirect()->route('admin.products.index',$product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Gate::authorize('delete-products');

        if ($product->inventories()->exists()) {
          session()->flash('swal',[
            'icon' => 'error',
            'title' => '¡Error!',
            'text' => 'No se puede eliminar el producto por que tiene inventarios asociados.',
        ]);
        }
        if ($product->purchaseOrders()->exists() || $product->quotes()->exists()) {
            session()->flash('swal',[
            'icon' => 'error',
            'title' => '¡Error!',
            'text' => 'No se puede eliminar el producto por que tiene órdenes de compra cotizaciones asociados.',
        ]);
        return redirect()->route('admin.products.index');
        }
        $product->delete();

        session()->flash('swal',[
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El producto se ha eliminado correctamente.'
        ]);
        return redirect()->route('admin.products.index');
    }

    public function dropzone(Request $request, Product $product)
    {
        Gate::authorize('update-products');

        $image = $product->images()->create([
            'path' => Storage::put('/images',$request->file('file')),
            'size' => $request->file('file')->getSize(),

        ]);

        return response()->json([
            'id' => $image->id,
            'path' => $image->path,
        ]);
    }
    // para mostrar los kardex
    public function kardex(Product $product)
    {
        Gate::authorize('read-products');

        return view('admin.products.kardex', compact('product'));
    }
    //Proceso de excel
    public function import()
    {
        Gate::authorize('create-products');

        return view('admin.products.import');
    }
}
