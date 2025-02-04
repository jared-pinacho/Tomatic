<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invernadero extends Model
{
    use SoftDeletes;

    protected $table = 'invernaderos';

    protected $primaryKey = 'id_invernadero';

    protected $fillable = ['nombre','dimension','fecha_creacion'];

    protected $casts = [
        'id_invernadero' => 'string', 
    ];

    public function cosechas(){
        return $this->hasMany(Cosecha::class,'id_invernadero');
    }


}
