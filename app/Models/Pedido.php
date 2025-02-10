<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;

    protected $table = 'pedidos';

    protected $primaryKey = 'id_pedido';

    protected $fillable = ['fecha', 'estatus','total', 'id_empleado', 'id_cliente'];

    protected $casts = [
        'id_pedido' => 'string',
    ];


    public function empleado () 
    {
        return $this->belongsTo(Empleado::class,'id_empleado');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }


    public function detallePedido()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido');
    }
}
