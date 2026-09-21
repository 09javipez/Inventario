<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('read-roles');
        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create-roles');
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create-roles');
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);
        $role = Role::create(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        session()->flash('swal',[
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El rol se ha creado correctamente.',
        ]);

        return redirect()->route('admin.roles.index', $role);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        Gate::authorize('update-roles');
        $permissions = Permission::all();
        return view('admin.roles.edit', compact('role','permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        Gate::authorize('update-roles');
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'. $role->id,
            'permissions' => 'nullable|array',
        ]);
        $role->update(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }else{
            $role->permissions()->detach();
        }

        session()->flash('swal',[
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El rol  ha sido actualizado correctamente.',
        ]);

        return redirect()->route('admin.roles.index', $role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        Gate::authorize('delete-roles');
        $role->delete();

        session()->flash('swal',[
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'El rol  ha sido eliminado correctamente.',
        ]);
        return redirect()->route('admin.roles.index');
    }
}
