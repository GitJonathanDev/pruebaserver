<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;


    protected $table = 'Compra';


    protected $primaryKey = 'codCompra';

 
    public $incrementing = true;

 
    protected $keyType = 'int';


    protected $fillable = [
        'fechaCompra',
        'montoTotal',
        'codEncargadoF',
        'codProveedorF',
    ];


    protected $casts = [
        'fechaCompra' => 'date',
        'montoTotal' => 'float',
        'codEncargadoF' => 'integer',
        'codProveedorF' => 'integer',
    ];

    public function encargado()
    {
        return $this->belongsTo(Encargado::class, 'codEncargadoF', 'carnetIdentidad');
    }


    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'codProveedorF', 'codProveedor');
    }


    public $timestamps = false;
}
