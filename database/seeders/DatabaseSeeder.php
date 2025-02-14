<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
   
    public function run(): void
    {
        // Crear usuario y guardarlo
        $user = new User();
        $user->name = "Admin";
        $user->email = "admin@gmail.com";
        $user->password = Hash::make('1234'); // Asegurar que sea string
        $user->rol = 1;
        $user->save(); // Guardar en la base de datos

 
    }
}
