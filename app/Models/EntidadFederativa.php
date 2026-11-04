<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class EntidadFederativa extends Model
{
    use SoftDeletes;
    protected $table = 'entidad_federativa';
    protected $fillable = ['nombre'];

        private static $validation_messages = [
            'nombre.required'           => 'El campo de nombre es requerido'
    ];

        public static function validarGenero($data, $id){
            $rules = [
                'nombre'                => 'required|between:1,100'
            ];
            return Validator::make($data, $rules, self::$validation_messages);
    }
}
