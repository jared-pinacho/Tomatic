<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Model

{
    use SoftDeletes;

    protected $table = 'empleados';

    protected $primaryKey = 'id_empleado';

    protected $fillable = ['nombre','apellido','edad','sexo','rol'];

    protected $casts = [
        'id_empleado' => 'string', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function ventas(){
        return $this->hasMany(Venta::class,'id_empleado');
    }

    public function pedidos(){
        return $this->hasMany(Pedido::class,'id_empleado');
    }
}
