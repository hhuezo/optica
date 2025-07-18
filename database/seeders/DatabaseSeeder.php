<?php

namespace Database\Seeders;

use App\Models\catalogo\TipoDocumento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        if (!Schema::hasColumn('documents', 'to_warehouse_id')) {
            DB::statement("ALTER TABLE `documents` ADD `to_warehouse_id` INT NULL AFTER `warehouses_id`");
        }

        Schema::table('clients', function (Blueprint $table) {
            $table->string('email', 100)->nullable();
            $table->string('reference_phone', 20)->nullable();
            $table->string('reference_name', 150)->nullable();
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->string('diagnostic', 255)->nullable();
            $table->string('service_for', 150)->nullable();
            $table->string('observation', 150)->nullable();
        });

        Schema::table('contract_details', function (Blueprint $table) {
            $table->string('right_eye_sphere', 30)->nullable();
            $table->string('right_eye_cylinder', 30)->nullable();
            $table->string('right_eye_axis', 30)->nullable();
            $table->string('right_eye_addition', 30)->nullable();
            $table->string('left_eye_sphere', 30)->nullable();
            $table->string('left_eye_cylinder', 30)->nullable();
            $table->string('left_eye_axis', 30)->nullable();
            $table->string('left_eye_addition', 30)->nullable();
        });

        $_users = DB::table('users_')->get();


        foreach ($_users as $_user) {
            $user = new User();
            $user->id = $_user->id;
            $user->name = $_user->name;
            $user->last_name = $_user->last_name;
            $user->email = $_user->user_name . '@mail.com';
            $user->username = $_user->user_name;
            $user->user_name = $_user->user_name;
            $user->statuses_id = $_user->statuses_id;

            $user->level = $_user->level;
            $user->comision_percentage = $_user->comision_percentage;
            $user->stores_id = $_user->stores_id;

            $user->password = $_user->password;
            $user->save();
        }





        $admin = User::factory()->create([
            'name' => 'admin',
            'username' => 'administrador',
            'user_name' => 'administrador',
            'email' => 'administrador@mail.com',
            'last_name' => '',
            'statuses_id' => 2,
            'level' => 1,
            'password' => Hash::make('12345678'),
        ]);

        $role = Role::firstOrCreate(['name' => 'administrador']);

        // Asignar el rol al usuario
        $admin->assignRole($role);




        Permission::firstOrCreate(['name' => 'menu seguridad']);
        Permission::firstOrCreate(['name' => 'menu administracion']);
        Permission::firstOrCreate(['name' => 'menu inventario']);
        Permission::firstOrCreate(['name' => 'menu ventas']);

        $role->givePermissionTo(Permission::all());


        Role::firstOrCreate(['name' => 'asesor']);
        $role = Role::find(2);

        $users = User::where('level',  2)->get();

        foreach ($users as $_user) {
            $_user->assignRole($role);
        }





        // Eliminar las claves foráneas erróneas
        Schema::table('contract_employees', function (Blueprint $table) {
            $table->dropForeign('contract_employees_users_users_id');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign('documents_users_users_id');
        });

        //cambiando a big int
        Schema::table('contract_employees', function (Blueprint $table) {
            $table->unsignedBigInteger('users_id')->change();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('users_id')->change();
        });

        // Crear las claves foráneas correctas apuntando a users(id)
        Schema::table('contract_employees', function (Blueprint $table) {
            $table->foreign('users_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('users_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });



        //deshabilitar reposision
        TipoDocumento::where('id', 4)->update(['statuses_id' => 3]);
    }
}
