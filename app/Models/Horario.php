<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Horario extends Model
{
    use HasFactory;


    protected $table = 'Horario';

    
    public $timestamps = false;

 
    protected $fillable = [
        'horaInicio',
        'horaFin',
    ];


    protected $primaryKey = 'codHorario';


    public $incrementing = true;


    protected $keyType = 'int';


    public function setHoraInicioAttribute($value)
    {
        $this->attributes['horaInicio'] = Carbon::createFromFormat('H:i', $value)->format('H:i:s');
    }


    public function setHoraFinAttribute($value)
    {
        $this->attributes['horaFin'] = Carbon::createFromFormat('H:i', $value)->format('H:i:s');
    }


    public function getHoraInicioAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->format('H:i');
    }

  
    public function getHoraFinAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->format('H:i');
    }
}
