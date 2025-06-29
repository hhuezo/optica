<?php

namespace App\Http\Controllers\seguridad;

use App\Http\Controllers\Controller;
use App\Models\seguridad\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::get();

        return view('seguridad.role.index', compact('roles'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Validar antes de crear
        $request->validate([
            'name' => 'required|unique:roles,name',
        ], [
            'name.required' => 'El campo rol es obligatorio.',
            'name.unique' => 'El rol ya existe en el sistema.',
        ]);
        try {

            // Si pasa la validación, se crea el rol
            $rol = Role::create(['name' => $request->name]);

            return back()->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar rol: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            // Redireccionar con mensaje genérico al usuario
            return back()
                ->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $permisos = Permission::get();

        return view('seguridad.role.edit', compact('role', 'permisos'));
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function updatePermission(Request $request)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
            'role_id' => 'required|exists:roles,id'
        ]);


        try {


            $permission = Permission::findOrFail($request->permission_id);
            $role = Role::findOrFail($request->role_id);


            if ($role->hasPermissionTo($permission->name)) {
                $role->revokePermissionTo($permission->name);
                return response()->json([
                    'success' => true,
                    'message' => 'Rol removido correctamente',
                    'action' => 'removed'
                ]);
            }

            $role->givePermissionTo($permission->name);
            return response()->json([
                'success' => true,
                'message' => 'Rol asignado correctamente',
                'action' => 'assigned'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }
}
