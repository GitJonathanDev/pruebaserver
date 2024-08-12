<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;


    protected $table = 'Venta';


    protected $primaryKey = 'codVenta';


    public $incrementing = true;


    protected $keyType = 'int';


    protected $fillable = [
        'fechaVenta',
        'montoTotal',
        'codEncargadoF',
        'codClienteF',
        'codPagoF',
    ];


    protected $casts = [
        'fechaVenta' => 'date',
        'montoTotal' => 'float',
        'codEncargadoF' => 'integer',
        'codClienteF' => 'integer',
        'codPagoF' => 'integer',
    ];


    public function encargado()
    {
        return $this->belongsTo(Encargado::class, 'codEncargadoF', 'codEncargado');
    }


    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'codClienteF', 'codCliente');
    }


    public function pago()
    {
        return $this->belongsTo(Pago::class, 'codPagoF', 'codPago');
    }


    public $timestamps = false;
}
