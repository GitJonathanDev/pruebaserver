<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;


    protected $table = 'Servicio';

    protected $primaryKey = 'codServicio';


    public $incrementing = true;


    protected $keyType = 'int';

  
    protected $fillable = [
        'nombre',
        'descripcion',
        'capacidad',
        'codHorarioF',
    ];


    protected $casts = [
        'capacidad' => 'integer',
        'codHorarioF' => 'integer',
    ];


    public function horario()
    {
        return $this->belongsTo(Horario::class, 'codHorarioF', 'codHorario');
    }


    public function precios()
    {
        return $this->hasMany(PrecioServicio::class, 'codServicioF', 'codServicio');
    }


    public $timestamps = false;
}
