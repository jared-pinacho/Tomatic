<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    
    use SoftDeletes;

    protected $table = 'productos';

    protected $primaryKey = 'id_producto';

    protected $fillable = ['nombre','tipo','precio','id_cosecha','id_categoria'];

    protected $casts = [
        'id_producto' => 'string', 
    ];

    public function cosecha () 
    {
        return $this->belongsTo(Cosecha::class,'id_cosecha');
    }

    public function categoria () 
    {
        return $this->belongsTo(Categoria::class,'id_categoria');
    }

}
