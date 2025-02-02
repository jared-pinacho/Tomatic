<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes;

    protected $table = 'categorias';

    protected $primaryKey = 'id_categoria';

    protected $fillable = ['nombre','descripcion'];

    protected $casts = [
        'id_categoria' => 'string', 
    ];

    public function productos(){
        return $this->hasMany(Producto::class,'id_categoria');
    }

    


}
