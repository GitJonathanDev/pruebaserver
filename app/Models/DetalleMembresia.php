<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleMembresia extends Model
{
    use HasFactory;
    

    protected $table = 'DetalleMembresia';


    protected $primaryKey = ['codServicio', 'codMembresia'];


    public $incrementing = false;

    public $timestamps = false;


    protected $fillable = [
        'fechaInicio',
        'fechaFin',
        'subTotal',
        'tipo',
        'codServicio',
        'codMembresia',
    ];


    protected $casts = [
        'fechaInicio' => 'date',
        'fechaFin' => 'date',
        'subTotal' => 'float',
        'codServicio' => 'integer',
        'codMembresia' => 'integer',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'codServicio', 'codServicio');
    }

    public function membresia()
    {
        return $this->belongsTo(Membresia::class, 'codMembresia', 'codMembresia');
    }
}
