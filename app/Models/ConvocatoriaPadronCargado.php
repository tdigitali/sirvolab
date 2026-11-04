<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class ConvocatoriaPadronCargado extends Model
{
    use SoftDeletes;
    protected $table = 'convocatoria_padron_cargado';
    protected $fillable = ['ruta','convocatoria_id','fecha_carga','registros_cargados'];

    private static $validation_messages = [
        'ruta.required'                                        => 'El campo de curp es requerido.',
        'ruta.between'                                         => 'El campo de ruta debe contener máximo 200 caracteres.',
        'fecha_carga.required'                                 => 'El campo de fecha de carga es requerido.',
        'convocatoria_id.required'                             => 'El campo de convocatoria es requerido.',
        'convocatoria_id.exists'                               => 'El campo de convocatoria debe estar en el catálogo.',
        'registros_cargados.required'                          => 'El campo de registros cargados es requerido.'
    ];

    public static function validarConvocatoriaPadron($data, $id){
        $rules = [
            'curp'                                             => 'required|between:18,18',
            'nombre_completo'                                  => 'required|between:1,200',
            'centro_trabajo_id'                                => 'required|exists:centro_trabajo,id',
            'entidad_federativa_id'                            => 'required|exists:entidad_federativa,id',
            'registros_cargados'                               => 'required',
        ];

        return Validator::make($data, $rules, self::$validation_messages);
    }
}
