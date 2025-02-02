<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model

{
    use SoftDeletes;

    protected $table = 'usuarios';

    protected $primaryKey = 'id_usuario';

    protected $fillable = ['nombre','apellido','username','password','rol'];

    protected $casts = [
        'id_usuario' => 'string', 
    ];

    public function ventas(){
        return $this->hasMany(Venta::class,'id_usuario');
    }

    public function pedidos(){
        return $this->hasMany(Pedido::class,'id_usuario');
    }
}
