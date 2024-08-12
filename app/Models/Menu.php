<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'Menu';


    protected $primaryKey = 'id';

  
    public $timestamps = false;


    protected $fillable = [
        'nombre',
        'url',
        'icono',
        'codTipoUsuarioF',
        'padreId'
    ];


    public function padre()
    {
        return $this->belongsTo(Menu::class, 'padreId', 'id');
    }

    public function hijos()
    {
        return $this->hasMany(Menu::class, 'padreId', 'id');
    }


    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'codTipoUsuarioF', 'codTipoUsuario');
    }
}
