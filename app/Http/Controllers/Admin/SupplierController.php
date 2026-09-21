<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Identity;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('read-suppliers');
        return view('admin.suppliers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create-suppliers');
        $identities = Identity::all();
        return view('admin.suppliers.create', compact('identities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create-suppliers');
        $data = $request->validate([
            'identity_id' => 'required|exists:identities,id',
            'document_number' => 'required|string|max:15|unique:suppliers,document_number',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:10',
        ]);
        $supplier = Supplier::create($data);

        session()->flash('swal',[
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El proveedor ha sido creado correctamente.',
        ]);

        return redirect()->route('admin.suppliers.index',$supplier);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        Gate::authorize('update-suppliers');
        $identities = Identity::all();
        return view('admin.suppliers.edit', compact('supplier','identities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        Gate::authorize('update-suppliers');
        $data = $request->validate([
            'identity_id' => 'required|exists:identities,id',
            'document_number' => 'required|string|max:15|unique:suppliers,document_number,' . $supplier->id,
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:10',
        ]);
        $supplier->update($data);

        session()->flash('swal',[
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El proveedor ha sido actualizado correctamente.',
        ]);

        return redirect()->route('admin.suppliers.index',$supplier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        Gate::authorize('delete-suppliers');
        if ($supplier->purchasesOrders()->exists() || $supplier->purchases()->exists()) {
            session()->flash('swal',[
            'icon' => 'error',
            'title' => '¡Error!',
            'text' => 'No se puede eliminar el proveedor por que tiene  ordenes de compra o compras asociadas.',
        ]);
        return redirect()->route('admin.products.index');
        }

        $supplier->delete();
        session()->flash('swal',[
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El proveedor ha sido eliminado correctamente.',
        ]);
        return redirect()->route('admin.suppliers.index',$supplier);
    }
}
