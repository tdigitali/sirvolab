<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Bitacora extends Model
{
    use SoftDeletes;
    protected $table = 'bitacora';
    protected $fillable = ['accion_realizada', 'fecha_accion','id_accion_realizada','modulo_id','usuario_id'];

        private static $validation_messages = [
            'accion_realizada.required'                  => 'El campo de accion_relizada es requerido',
            'fecha_accion.required'                      => 'El campo de fecha_accion es requerido',
            'accion_realizada_id.required'               => 'El campo de id_accion_realizada es requerido',
            'modulo_id.required'                         => 'El campo de modulo es requerido',
            'usuario_id.required'                        => 'El campo de usuario_id es requerido',
    ];

        public static function validateBitacora($data, $id){
            $rules = [
                'accion_realizada'                       => 'required',
                'fecha_accion'                           => 'required',
                'usuario_id'                             => 'required',
                'modulo_id'                              => 'required',
                'usuario_id'                             => 'required',
            ];
            return Validator::make($data, $rules, self::$validation_messages);
    }
}
