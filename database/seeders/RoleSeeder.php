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
            Permission::create(['name' => $permission]);
        }

        Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());
        Role::create(['name' => 'editor'])
            ->givePermissionTo([
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
        Role::Create(['name' => 'viewer'])
            ->givePermissionTo([
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
        User::factory()->create([
            'name' => 'Javier',
            'email' => 'javipez1999@proton.me',
            'password' => bcrypt('12345678'),
        ])->assignRole('admin');
        User::factory()->create([
            'name' => 'Javier',
            'email' => 'javipez1999@gmail.com',
            'password' => bcrypt('12345678'),
        ])->assignRole('editor');
        User::factory()->create([
            'name' => 'viewer',
            'email' => 'prueba@gmail.com',
            'password' => bcrypt('12345678'),
        ])->assignRole('viewer');
    }
}
