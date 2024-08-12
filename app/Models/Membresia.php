<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    use HasFactory;


    protected $table = 'Membresia';


    protected $primaryKey = 'codMembresia';

 
    public $incrementing = true;


    protected $keyType = 'int';

    protected $fillable = [
        'descripcion',
        'precioTotal',
        'codClienteF',
        'codEncargadoF',
        'codPagoF',
    ];

    protected $casts = [
        'precioTotal' => 'float',
        'codClienteF' => 'integer',
        'codEncargadoF' => 'integer',
        'codPagoF' => 'integer',
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'codClienteF', 'carnetIdentidad');
    }


    public function encargado()
    {
        return $this->belongsTo(Encargado::class, 'codEncargadoF', 'carnetIdentidad');
    }


    public function pago()
    {
        return $this->belongsTo(Pago::class, 'codPagoF', 'codPago');
    }


    public function detalles()
    {
        return $this->hasMany(DetalleMembresia::class, 'codMembresia', 'codMembresia');
    }

 
    public $timestamps = false;
}
