<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'Pago';

    protected $primaryKey = 'codPago';

    protected $fillable = [
        'fechaPago',
        'monto',
        'estado',       
        'codClienteF', 
    ];


    protected $casts = [
        'fechaPago' => 'date',
        'monto' => 'float',
        'codClienteF' => 'integer', 
    ];


    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'codClienteF', 'codCliente');
    }

    public $timestamps = false;
}
