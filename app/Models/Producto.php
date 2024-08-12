<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'Producto';


    protected $primaryKey = 'codProducto';

 
    public $incrementing = false;


    protected $fillable = [
        'codProducto', 
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen_url', 
        'codCategoriaF',
    ];


    protected $casts = [
        'precio' => 'float',
        'stock' => 'integer',
        'codCategoriaF' => 'integer', 
    ];


    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'codCategoriaF', 'codCategoria');
    }


    public $timestamps = false;
}
