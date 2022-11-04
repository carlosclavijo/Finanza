<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'monto',
        'user_id',
        'cuenta_id',
        'categoria_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }

    public function cuenta() {
        return $this->belongsTo(Cuenta::class);
    }

    public function movimientotransferencia() {
        return $this->hasOne(MovimientoTransferencia::class);
    }
}
