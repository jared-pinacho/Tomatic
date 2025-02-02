<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;

    protected $table = 'pedidos';

    protected $primaryKey = 'id_pedido';

    protected $fillable = ['fecha', 'estatus','total', 'id_usuario', 'id_cliente'];

    protected $casts = [
        'id_pedido' => 'string',
    ];


    public function usuario () 
    {
        return $this->belongsTo(Usuario::class,'id_usuario');
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
