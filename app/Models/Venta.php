<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Venta extends Model
{
    use SoftDeletes;

    protected $table = 'ventas';

    protected $primaryKey = 'id_venta';

    protected $fillable = ['fecha', 'total', 'id_usuario', 'id_cliente'];

    protected $casts = [
        'id_venta' => 'string',
    ];


    public function usuario () 
    {
        return $this->belongsTo(Usuario::class,'id_usuario');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
     }

     public function detalleVenta()
     {
         return $this->hasMany(DetalleVenta::class, 'id_venta');
     }
}
