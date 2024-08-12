<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;


    protected $table = 'DetalleVenta';


    protected $primaryKey = ['codVenta', 'codProducto'];


    public $incrementing = false;


    public $timestamps = false;


    protected $fillable = [
        'precioV',
        'cantidad',
        'codVenta',
        'codProducto',
    ];


    protected $casts = [
        'precioV' => 'float',
        'cantidad' => 'integer',
        'codVenta' => 'integer',
        'codProducto' => 'integer',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'codVenta', 'codVenta');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'codProducto', 'codProducto');
    }
}
