<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimientotransferencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'movimiento_id',
        'cuenta_id'
    ];

    public function movimiento() {
        return $this->belongsTo(Movimiento::class);
    }
}
