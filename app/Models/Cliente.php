<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $table = 'clientes';

    protected $primaryKey = 'id_cliente';

    protected $fillable = ['nombre','apellido','telefono'];

    protected $casts = [
        'id_cliente' => 'string', 
    ];

    public function ventas(){
        return $this->hasMany(Venta::class,'id_cliente');
    }  
    
    public function pedidos(){
        return $this->hasMany(Pedido::class,'id_cliente');
    }

}
