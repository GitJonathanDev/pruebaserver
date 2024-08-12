<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitasPagina extends Model
{
    use HasFactory;
    protected $table = 'visitasPagina';
    public $timestamps = false;
    protected $fillable = [
        'nombrePagina',
        'conteoVisitas',
    ];

    protected $casts = [
        'conteoVisitas' => 'integer',
    ];
}
