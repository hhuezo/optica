<?php

namespace App\Http\Controllers\seguridad;

use App\Http\Controllers\Controller;
use App\Models\seguridad\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::get();
        $roles = Role::get();
        return view('seguridad.usuario.index', compact('usuarios', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        // Validaciones
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:50',
                'username' => 'required|string|max:50|unique:users,username',
                'password' => 'required|string|min:6',
                'role_id' => 'required|exists:roles,id',
            ],
            [
                'name.required' => 'El nombre es obligatorio.',
                'name.string' => 'El nombre debe ser una cadena de texto.',
                'name.max' => 'El nombre no debe superar los 255 caracteres.',

                'last_name.required' => 'El apellido es obligatorio.',
                'last_name.string' => 'El apellido debe ser una cadena de texto.',
                'last_name.max' => 'El apellido no debe superar los 50 caracteres.',

                'username.required' => 'El nombre de usuario es obligatorio.',
                'username.string' => 'El nombre de usuario debe ser una cadena de texto.',
                'username.max' => 'El nombre de usuario no debe superar los 50 caracteres.',
                'username.unique' => 'Este nombre de usuario ya está en uso.',

                'password.required' => 'La contraseña es obligatoria.',
                'password.string' => 'La contraseña debe ser una cadena de texto.',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres.',

                'role_id.required' => 'El rol es obligatorio.',
                'role_id.exists' => 'El rol seleccionado no existe.',
            ]
        );

        try {
            DB::beginTransaction();

            // Crear usuario
            $user = new User();
            $user->name = $validated['name'];
            $user->last_name = $validated['last_name'];
            $user->username = $validated['username'];
            $user->password = Hash::make($validated['password']);
            $user->email = $validated['username'] . '@example.com';
            $user->active = 1;
            $user->save();

            // Asignar rol
            $role = Role::findOrFail($validated['role_id']);
            $user->assignRole($role->name);

            DB::commit();

            return redirect()->back()->with('success', 'Usuario creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al crear usuario: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Hubo un error al crear el usuario.');
        }
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
        $usuario = User::findOrFail($id);
        $roles = Role::get();
        return view('seguridad.usuario.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:50',
                'user_name' => 'required|string|max:50|unique:users,user_name,' . $id,
                'role_id' => 'required|exists:roles,id',
            ],
            [
                'name.required' => 'El nombre es obligatorio.',
                'name.string' => 'El nombre debe ser una cadena de texto.',
                'name.max' => 'El nombre no debe superar los 255 caracteres.',

                'last_name.required' => 'El apellido es obligatorio.',
                'last_name.string' => 'El apellido debe ser una cadena de texto.',
                'last_name.max' => 'El apellido no debe superar los 50 caracteres.',

                'user_name.required' => 'El nombre de usuario es obligatorio.',
                'user_name.string' => 'El nombre de usuario debe ser una cadena de texto.',
                'user_name.max' => 'El nombre de usuario no debe superar los 50 caracteres.',
                'user_name.unique' => 'Este nombre de usuario ya está en uso.',

                'role_id.required' => 'El rol es obligatorio.',
                'role_id.exists' => 'El rol seleccionado no existe.',
            ]
        );

        try {

            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->name = $validated['name'];
            $user->last_name = $validated['last_name'];
            $user->user_name = $validated['user_name'];
            $user->sales_percentage = $request->sales_percentage;
            $user->collection_percentage = $request->collection_percentage;
            $user->save();

            // Asignar rol
            $role = Role::findOrFail($validated['role_id']);
            $user->syncRoles([$role->name]);
            DB::commit();

            return redirect()->back()->with('success', 'Usuario modificado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al modificar usuario: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Hubo un error al modificar el usuario.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $usuario = User::findOrFail($id);

            $usuario->active = $usuario->active == 1 ? 0 : 1;
            $usuario->save();

            return response()->json([
                'success' => true,
                'message' => $usuario->active == 1 ? 'Usuario activado' : 'Usuario desactivado',
                'nuevo_estado' => $usuario->active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request, $id)
    {
        // Validaciones de la contraseña
        $validated = $request->validate([
            'password' => 'required|string|min:6',
        ], [
            'id.required' => 'El usuario no es válido.',
            'id.exists' => 'El usuario no existe en la base de datos.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        try {
            // Buscar al usuario
            $user = User::findOrFail($id);

            // Actualizar la contraseña
            $user->password = Hash::make($request->password);
            $user->save();

            // Retornar con mensaje de éxito
            return back()->with('success', 'La contraseña se ha actualizado correctamente.');
        } catch (\Exception $e) {
            // Si ocurre un error, retornamos con mensaje de error
            return back()->with('error', 'Hubo un error al actualizar la contraseña. Intenta nuevamente.');
        }
    }
}
