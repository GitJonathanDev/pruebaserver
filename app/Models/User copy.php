<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $table = 'Usuario';


    protected $primaryKey = 'codUsuario';
    protected $keyType = 'bigint';
    public $incrementing = true;

 
    protected $fillable = [
        'nombreUsuario',
        'email',
        'password',
        'estadoBloqueado',
        'codTipoUsuarioF', 
    ];


    protected $hidden = [
        'password',
    ];


    protected $casts = [
        'estadoBloqueado' => 'boolean',
        'codUsuario' => 'integer',
    ];


    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'codTipoUsuarioF'); 
    }

 
    public function setPasswordAttribute($password) {
        $this->attributes['password'] = bcrypt($password);
    }

    
    public $timestamps = false;
}
