<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cosecha extends Model
{
    use SoftDeletes;

    protected $table = 'cosechas';

    protected $primaryKey = 'id_cosecha';

    protected $fillable = ['fecha_inicio','fecha_final','nombre','estado','monto_total','id_invernadero'];

    protected $casts = [
        'id_cosecha' => 'string', 
    ];



    public function invernadero () 
    {
        return $this->belongsTo(Invernadero::class,'id_invernadero');
    }


    public function productos(){
        return $this->hasMany(Producto::class,'id_cosecha');
    }
}
