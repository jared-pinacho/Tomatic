<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallePedido extends Model
{
    use SoftDeletes;

    protected $table = 'detalle_pedido';


    protected $fillable = ['cantidad','id_pedido','id_producto'];

    

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_venta');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
