<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;


    protected $table = 'Categoria';


    protected $primaryKey = 'codCategoria';


    public $incrementing = true;


    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
    ];


    protected $casts = [
        'nombre' => 'string',
    ];

    public $timestamps = false;
}
