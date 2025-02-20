<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HoraDefecto extends Model
{
    use HasFactory;
    protected $table = 'hora_defecto';
    protected $fillable = ['hora_entrada_Defecto', 'hora_salida_Defecto'];
}
