<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class MotivoRechazo extends Model
{
    use SoftDeletes;
    protected $table = 'motivo_rechazo';
    protected $fillable = ['nombre'];

        private static $validation_messages = [
            'nombre.required'           => 'El campo de nombre es requerido'
    ];

        public static function validarMotivos($data, $id){
            $rules = [
                'nombre'                => 'required|between:1,255'
            ];
            return Validator::make($data, $rules, self::$validation_messages);
    }
}
