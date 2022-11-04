<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'saldo',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function movimiento() {
        return $this->hasMany(Movimiento::class);
    }

    public function movimientotransferencia() {
        return $this->hasMany(MovimientoTransferencia::class);
    }
}
