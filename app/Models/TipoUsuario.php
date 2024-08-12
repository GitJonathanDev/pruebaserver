<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    use HasFactory;


    protected $table = 'TipoUsuario';


    protected $primaryKey = 'codTipoUsuario';


    protected $keyType = 'bigint';


    public $incrementing = true;


    public $timestamps = false;


    protected $fillable = [
        'descripcion',
    ];


    protected $casts = [
        'descripcion' => 'string',
        'codTipoUsuario' => 'integer'
    ];
}
