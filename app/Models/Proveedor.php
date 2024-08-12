<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;


    protected $table = 'Proveedor';


    protected $primaryKey = 'codProveedor';


    public $incrementing = false;

    protected $keyType = 'int';


    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
    ];

    protected $casts = [
        'nombre' => 'string',
        'direccion' => 'string',
        'telefono' => 'integer',
    ];


    public $timestamps = false;
}
