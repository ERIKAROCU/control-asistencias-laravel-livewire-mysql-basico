<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feriado extends Model
{
    use HasFactory;

    protected $fillable = ['fecha', 'descripcion'];

    /**
     * Verifica si una fecha es feriado.
     *
     * @param string $fecha
     * @return bool
     */
    public static function esFeriado($fecha)
    {
        return self::whereDate('fecha', $fecha)->exists();
    }
}

