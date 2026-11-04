<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class ConvocatoriaCentroTrabajo extends Model
{
    use SoftDeletes;
    protected $table = 'convocatoria_centro_trabajo';
    protected $fillable = ['nombre'];

        private static $validation_messages = [
            'nombre.required'           => 'El campo de nombre es requerido'
    ];

        public static function validarCentroTrabajo($data, $id){
            $rules = [
                'nombre'                => 'required|between:1,200'
            ];
            return Validator::make($data, $rules, self::$validation_messages);
    }
}
