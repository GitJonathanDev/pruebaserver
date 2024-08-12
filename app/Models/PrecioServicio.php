<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioServicio extends Model
{
    use HasFactory;


    protected $table = 'PrecioServicio'; 

    protected $primaryKey = 'codPrecioServicio';


    public $incrementing = true;


    public $timestamps = false;


    protected $fillable = [
        'tipo',
        'precio',
        'codServicioF',
    ];
    protected $casts = [
        'precio' => 'float',
        'codServicioF' => 'integer',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'codServicioF', 'codServicio');
    }
}
