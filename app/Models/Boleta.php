<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Boleta extends Model
{
    use SoftDeletes;
    protected $table = 'boleta';
    protected $fillable = ['cargo_postulacion','numero_boleta'];

    private static $validation_messages = [
        'cargo_postulacion.required'                    => 'El campo de cargo a postular es requerido.',
        'cargo_postulacion.between'                     => 'El de cargo a postular tener máximo 200 caracteres.',
        'numero_boleta.integer'                         => 'El campo de número de boleta debe ser un número entero.',
        'numero_boleta.min'                             => 'El campo de número de boleta debe ser mayor a 0.',
        'numero_boleta.required'                        => 'El campo de número de boleta es requerido.',
        'numero_boleta.max'                             => 'El campo de número de boleta debe ser máximo 10'
    ];

    public static function validarBoleta($data){
        $rules = [
            'cargo_postulacion'                         => 'required|between:1,200',
            'numero_boleta'                             => 'required|integer|min:1|max:10'
        ];

        return Validator::make($data, $rules, self::$validation_messages);
    }
}
