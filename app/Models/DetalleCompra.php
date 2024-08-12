<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;


    protected $table = 'DetalleCompra';

  
    protected $primaryKey = ['codCompra', 'codProducto'];


    public $incrementing = false;


    public $timestamps = false;

    protected $fillable = [
        'precioC',
        'cantidad',
        'codCompra',
        'codProducto',
    ];


    protected $casts = [
        'precioC' => 'float',
        'cantidad' => 'integer',
        'codCompra' => 'integer',
        'codProducto' => 'integer',
    ];


    public function compra()
    {
        return $this->belongsTo(Compra::class, 'codCompra', 'codCompra');
    }


    public function producto()
    {
        return $this->belongsTo(Producto::class, 'codProducto', 'codProducto');
    }
}
