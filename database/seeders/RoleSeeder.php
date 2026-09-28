<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Hecho para poder correrse varias veces sin dar error (usa
     * firstOrCreate / updateOrCreate en vez de create), porque en el
     * plan gratis de Render no hay acceso a Shell y este seeder se
     * ejecuta automáticamente cada vez que arranca el contenedor.
     */
    public function run(): void
    {
        $permissions = [
          //Categories
          'create-categories',
          'read-categories',
          'update-categories',
          'delete-categories',

          //Products
          'create-products',
          'read-products',
          'update-products',
          'delete-products',

          //Almacenes
          'create-warehouses',
          'read-warehouses',
          'update-warehouses',
          'delete-warehouses',

          //Proveedores
          'create-suppliers',
          'read-suppliers',
          'update-suppliers',
          'delete-suppliers',

          //Ordenes de compa
          'create-purchase-orders',
          'read-purchase-orders',
          'update-purchase-orders',
          'delete-purchase-orders',

          //Compras
          'create-purchases',
          'read-purchases',
          'update-purchases',
          'delete-purchases',

          //Clientes
          'create-customers',
          'read-customers',
          'update-customers',
          'delete-customers',

          //Cotizaciones
          'create-quotes',
          'read-quotes',
          'update-quotes',
          'delete-quotes',

          //ventas
          'create-sales',
          'read-sales',
          'update-sales',
          'delete-sales',

          //Movimientos
          'create-movements',
          'read-movements',
          'update-movements',
          'delete-movements',

          //Transferencias
          'create-transfers',
          'read-transfers',
          'update-transfers',
          'delete-transfers',

          //Reportes
          'read-top-products',
          'read-top-customers',
          'read-top-suppliers',
          'read-low-stock',

          //Usuarios
          'create-users',
          'read-users',
          'update-users',
          'delete-users',

          //Roles
          'read-roles',
          'create-roles',
          'update-roles',
          'delete-roles',

          //Permisos
          'create-permissions',
          'read-permissions',
          'update-permissions',
          'delete-permissions',

          //Ajustes
          'read-settings',
          'update-settings',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $editorRole->syncPermissions([
                'create-categories',
                'read-categories',
                'update-categories',
                'delete-categories',
                'create-products',
                'read-products',
                'update-products',
                'delete-products',
                'create-warehouses',
                'read-warehouses',
                'update-warehouses',
                'delete-warehouses',
                'create-suppliers',
                'read-suppliers',
                'update-suppliers',
                'delete-suppliers',
                'create-customers',
                'read-customers',
                'update-customers',
                'delete-customers',
            ]);

        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);
        $viewerRole->syncPermissions([
                'read-categories',
                'read-products',
                'read-warehouses',
                'read-suppliers',
                'read-customers',
                'read-purchase-orders',
                'read-purchases',
                'read-quotes',
                'read-sales',
                'read-movements',
                'read-transfers',
                'read-top-products',
                'read-top-customers',
                'read-top-suppliers',
                'read-low-stock',
                'read-users',
                'read-roles',
                'read-permissions',
            ]);

        $admin = User::firstOrCreate(
            ['email' => 'javipez1999@proton.me'],
            [
                'name' => 'Javier',
                'password' => bcrypt('12345678'),
            ]
        );
        $admin->syncRoles(['admin']);

        $editor = User::firstOrCreate(
            ['email' => 'javipez1999@gmail.com'],
            [
                'name' => 'Javier',
                'password' => bcrypt('12345678'),
            ]
        );
        $editor->syncRoles(['editor']);

        $viewer = User::firstOrCreate(
            ['email' => 'prueba@gmail.com'],
            [
                'name' => 'viewer',
                'password' => bcrypt('12345678'),
            ]
        );
        $viewer->syncRoles(['viewer']);
    }
}
